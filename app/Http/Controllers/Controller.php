<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\AdsBanner;
use App\Models\Banner;
use App\Models\BlogPost;
use App\Models\Breadcrumb;
use App\Models\Career;
use App\Models\CareerRole;
use App\Models\CertificateLicense;
use App\Models\Certification;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Office;
use App\Models\People;
use App\Models\PrivacyPolicy;
use App\Models\Seo;
use App\Models\Story;
use App\Models\TermCondition;
use App\Models\User;
use App\Models\WhistleBlower;
use App\Models\WhyCyberpay;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function admin_login(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $input = $request->only(['email', 'password']);

        $admin = User::query()->where('email', $request->email)->first();

        if ($admin && !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect Password!',
            ], 401);
        }

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => "Email doesn't exist",
            ], 401);
        }

        if($admin->status == 'inactive')
        {
            return response()->json([
                'success' => false,
                'message' => "Account deactivated, kindly contact the site administrator",
            ], 401);
        }

        // authentication attempt
        if (auth()->attempt($input, $request->get('remember'))) {

            // Get the old token
            // $oldToken = $admin->tokens->where('name', 'API TOKEN')->first();

            // if($oldToken)
            // {
            //     // Revoke the old token
            //     $oldToken->delete();
            // }

            $token = $admin->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Admin logged in succesfully.',
                'token' => $token,
                'data' => $admin
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Admin authentication failed."
            ], 401);
        }
    }

    function submitContact(Request $request)
    {
        // Define your validation rules
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        // If validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please see errors parameter for details.',
                'errors' => $validator->errors()
            ], 422);
        }
        // Process form data (e.g., send email, save to database)
        Contact::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'subject' => $request->subject,
            'description' => $request->message,
        ]);

        /** Store information to include in mail in $data as an array */
        $data = array(
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'subject' => $request->subject,
            'description' => $request->message,
            'created_at' => now(),
            'admin' => 'support@cyberpay.net.ng',
        );

        /** Send message to the admin */
        Mail::send('emails.contact', $data, function ($m) use ($data) {
            $m->to($data['admin'])->subject(config('app.name').' Contact Form Notification');
        });

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! Your message has been successfully sent. We will get back to you shortly.',
        ], 200);
    }

    function submitWhistleBlower(Request $request)
    {
        // Define your validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // If validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please see errors parameter for details.',
                'errors' => $validator->errors()
            ], 422);
        }

        $documentUrl = null;

        if($request->hasFile('document')) {
            $documentPath = $request->file('document')->getRealPath();
            $filename = $request->file('document')->getClientOriginalName();

            // Upload to Cloudinary
            $uploadResponse = cloudinary()->uploadFile($documentPath, [
                'folder' => config('app.name'),
                'public_id' => pathinfo($filename, PATHINFO_FILENAME),
                'use_filename' => true,
            ]);

            $documentUrl = $uploadResponse->getSecurePath();
        }

        // Process form data (e.g., send email, save to database)
        WhistleBlower::create([
            'name' => $request->name ?? 'Annonymous',
            'email' => $request->email ?? 'Annonymous',
            'phone' => $request->phone ?? 'Annonymous',
            'description' => $request->description ?? 'Annonymous',
            'document' => $documentUrl, // Save document URL in the database
        ]);

        /** Store information to include in mail in $data as an array */
        $data = [
            'name' => $request->name ?? 'Annonymous',
            'email' => $request->email ?? 'Annonymous',
            'phone' => $request->phone ?? 'Annonymous',
            'description' => $request->description ?? 'Annonymous',
            'document_url' => $documentUrl,
            'created_at' => now(),
            'admin' => 'support@cyberpay.net.ng',
        ];

        /** Send message to the admin */
        Mail::send('emails.whistleBlower', $data, function ($m) use ($data) {
            $m->to($data['admin'])->subject(config('app.name').' Whistle Blower Form Notification');
        });

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Thank you for reaching out! Your whistleblower report has been successfully submitted. We will review the information and get back to you shortly if necessary.',
        ], 200);
    }

    function submitCareer(Request $request)
    {
        // Define your validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'gender' => 'required|string',
            'date_of_birth' => 'required',
            'state' => 'required|string',
            'location' => 'required|string',
            'yearOfExperience' => 'required|integer',
            'levelOfExperience' => 'required|string',
            'role' => 'required|string',
            'courseOfStudy' => 'required|string',
            'graduatingGrade' => 'required|string',
            'yearOfGraduation' => 'required|integer',
            'cv' => 'required|file|mimes:pdf,doc,docx', // Include image mime types
        ]);

        // If validation fails, return the error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please see errors parameter for details.',
                'errors' => $validator->errors()
            ], 422);
        }

        $cvUrl = null;

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->getRealPath();
            $filename = $request->file('cv')->getClientOriginalName();

            // Upload to Cloudinary
            $uploadResponse = Cloudinary::uploadFile($cvPath, [
                'folder' => config('app.name') . '/cv',
                'public_id' => pathinfo($filename, PATHINFO_FILENAME),
                'use_filename' => true,
            ]);

            $cvUrl = $uploadResponse->getSecurePath();
        }

        // Save the data to the database
        Career::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'state' => $request->state,
            'location' => $request->location,
            'yearOfExperience' => $request->yearOfExperience,
            'levelOfExperience' => $request->levelOfExperience,
            'role' => $request->role,
            'courseOfStudy' => $request->courseOfStudy,
            'graduatingGrade' => $request->graduatingGrade,
            'yearOfGraduation' => $request->yearOfGraduation,
            'cv' => $cvUrl, // Save CV URL in the database
        ]);

        /** Store information to include in mail in $data as an array */
        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'state' => $request->state,
            'location' => $request->location,
            'yearOfExperience' => $request->yearOfExperience,
            'levelOfExperience' => $request->levelOfExperience,
            'role' => $request->role,
            'courseOfStudy' => $request->courseOfStudy,
            'graduatingGrade' => $request->graduatingGrade,
            'yearOfGraduation' => $request->yearOfGraduation,
            'cv' => $cvUrl,
            'created_at' => now(),
            'admin' => 'support@cyberpay.net.ng',
        ];

        /** Send message to the admin */
        Mail::send('emails.career', $data, function ($message) use ($data) {
            $message->to($data['admin'])
                ->subject(config('app.name') . ' Career Form Submission');
        });

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Thank you for submitting your career information! We will review your application and get back to you shortly if necessary.',
        ], 200);
    }

    function get_blogs(Request $request)
    {
        // Check if blog_id is provided
        if ($request->has('blog_id')) {

            $blog = BlogPost::find($request->query('blog_id'));

            if (!$blog) {
                return response()->json(['success' => false, 'message' => "Selected blog does not exist."], 401);
            }

            $blog->update([
                'views' => $blog->view + 1
            ]);

            return response()->json(['success' => true, 'data' => $blog], 200);
        }

        // If no blog_id is provided, return all blogs
        $blogs = BlogPost::latest()->get();

        return response()->json(['success' => true, 'data' => $blogs], 200);
    }

    function get_faqs()
    {
        $faqs = Faq::latest()->get();

        return response()->json(['success' => true, 'data' => $faqs], 200);
    }

    function get_banners()
    {
        $banners = Banner::latest()->get();

        return response()->json(['success' => true, 'data' => $banners], 200);
    }

    function get_certificate_licenses()
    {
        $certificateLicenses = CertificateLicense::latest()->get();

        return response()->json(['success' => true, 'data' => $certificateLicenses], 200);
    }

    function get_certifications()
    {
        $certifications = Certification::latest()->get();

        return response()->json(['success' => true, 'data' => $certifications], 200);
    }

    public function office()
    {
        $office = Office::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $office
        ], 200);
    }

    public function ads_banners()
    {
        $ads = AdsBanner::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $ads
        ], 200);
    }

    public function career_roles()
    {
        $roles = CareerRole::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ], 200);
    }

    public function breadcrumbs()
    {
        $breadcrumb = Breadcrumb::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $breadcrumb
        ], 200);
    }

    public function peoples()
    {
        $peoples = People::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $peoples
        ], 200);
    }

    public function stories()
    {
        $stories = Story::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $stories
        ], 200);
    }

    public function seos()
    {
        $seos = Seo::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $seos
        ], 200);
    }

    public function abouts()
    {
        $about = About::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $about
        ], 200);
    }

    public function why_cyberpay()
    {
        $why = WhyCyberpay::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $why
        ], 200);
    }

    public function privacy_policies()
    {
        $policies = PrivacyPolicy::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $policies
        ], 200);
    }

    public function terms_conditions()
    {
        $tc = TermCondition::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $tc
        ], 200);
    }
}
