<?php

namespace App\Http\Controllers;

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
use App\Models\Story;
use App\Models\User;
use App\Models\WhistleBlower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Mail;
use Str;

class AdminController extends Controller
{
    public function getContact()
    {
        $contactUs = Contact::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $contactUs
        ], 200);
    }

    public function deleteContact(Request $request)
    {
        $contact = Contact::find($request->contact_id);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact us detials not found.',
            ], 401);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact us details deleted successfully.',
        ], 200);
    }

    public function getCareer()
    {
        $career = Career::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $career
        ], 200);
    }

    public function deleteCareer(Request $request)
    {
        $career = Career::find($request->career_id);

        if (!$career) {
            return response()->json([
                'success' => false,
                'message' => 'Career detials not found.',
            ], 401);
        }

        $career->delete();

        return response()->json([
            'success' => true,
            'message' => 'Career details deleted successfully.',
        ], 200);
    }

    public function getWhistleBlower()
    {
        $whistleBlower = WhistleBlower::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $whistleBlower
        ], 200);
    }

    public function deleteWhistleBlower(Request $request)
    {
        $whistleBlower = WhistleBlower::find($request->wb_id);

        if (!$whistleBlower) {
            return response()->json([
                'success' => false,
                'message' => 'Whistle Blower detials not found.',
            ], 401);
        }

        $whistleBlower->delete();

        return response()->json([
            'success' => true,
            'message' => 'Whistle Blower details deleted successfully.',
        ], 200);
    }

    public function admin_blogs()
    {
        $posts = BlogPost::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ], 200);
    }

    public function admin_blog_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => 'required|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
        $filename = pathinfo($file, PATHINFO_FILENAME);

        $response = cloudinary()->uploadFile($request->image->getRealPath(),
        [
            'folder' => config('app.name') . '/blog',
            "public_id" => $filename,
            "use_filename" => TRUE
        ])->getSecurePath();

        BlogPost::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $response,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog Published Successfully!'
        ], 200);
    }

    public function admin_blog_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'post_id' => ['required', 'numeric', 'exists:blog_posts,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => 'nullable|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = BlogPost::find($request->post_id);

        if (request()->hasFile('image')) {

            $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $response = cloudinary()->uploadFile($request->image->getRealPath(),
            [
                'folder' => config('app.name').'/blog',
                "public_id" => $filename,
                "use_filename" => TRUE
            ])->getSecurePath();

            if($post->image)
            {
                $token = explode('/', $post->image);
                $token2 = explode('.', $token[sizeof($token)-1]);
                cloudinary()->destroy(config('app.name').'/blog/'.$token2[0]);
            }
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $response ?? $post->image
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully!'
        ], 200);
    }

    public function admin_blog_delete(Request $request)
    {
        $post = BlogPost::find($request->post_id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found.',
            ], 401);
        }

        if($post->image)
        {
            $token = explode('/', $post->image);
            $token2 = explode('.', $token[sizeof($token)-1]);
            cloudinary()->destroy(config('app.name').'/blog/'.$token2[0]);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully.',
        ], 200);
    }

    public function admin_faqs()
    {
        $faqs = Faq::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $faqs
        ], 200);
    }

    public function admin_faqs_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'type' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        Faq::create([
            'type' => $request->type,
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FAQ Added Successfully!'
        ], 200);
    }

    public function admin_faqs_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'faq_id' => ['required', 'numeric', 'exists:faqs,id'],
            'type' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $faq = Faq::find($request->faq_id);

        $faq->update([
            'type' => $request->type,
            'question' => $request->question,
            'answer' => $request->answer
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FAQ updated successfully!'
        ], 200);
    }

    public function admin_faqs_delete(Request $request)
    {
        $faq = Faq::find($request->faq_id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.',
            ], 401);
        }

        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully.',
        ], 200);
    }

    public function admin_banners()
    {
        $banners = Banner::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $banners
        ], 200);
    }

    public function admin_banner_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => 'required|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
        $filename = pathinfo($file, PATHINFO_FILENAME);

        $response = cloudinary()->uploadFile($request->image->getRealPath(),
        [
            'folder' => config('app.name') . '/banners',
            "public_id" => $filename,
            "use_filename" => TRUE
        ])->getSecurePath();

        Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $response,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner Added Successfully!'
        ], 200);
    }

    public function admin_banner_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'banner_id' => ['required', 'numeric', 'exists:banners,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => 'nullable|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $banner = Banner::find($request->banner_id);

        if (request()->hasFile('image')) {

            $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $response = cloudinary()->uploadFile($request->image->getRealPath(),
            [
                'folder' => config('app.name').'/banners',
                "public_id" => $filename,
                "use_filename" => TRUE
            ])->getSecurePath();

            if($banner->image)
            {
                $token = explode('/', $banner->image);
                $token2 = explode('.', $token[sizeof($token)-1]);
                cloudinary()->destroy(config('app.name').'/banners/'.$token2[0]);
            }
        }

        $banner->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $response ?? $banner->image
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully!'
        ], 200);
    }

    public function admin_banner_delete(Request $request)
    {
        $banner = Banner::find($request->banner_id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner not found.',
            ], 401);
        }

        if($banner->image)
        {
            $token = explode('/', $banner->image);
            $token2 = explode('.', $token[sizeof($token)-1]);
            cloudinary()->destroy(config('app.name').'/banners/'.$token2[0]);
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully.',
        ], 200);
    }

    public function admin_certificate_licenses()
    {
        $cls = CertificateLicense::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $cls
        ], 200);
    }

    public function admin_certificate_license_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'image' => 'required|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
        $filename = pathinfo($file, PATHINFO_FILENAME);

        $response = cloudinary()->uploadFile($request->image->getRealPath(),
        [
            'folder' => config('app.name') . '/certificateLicenses',
            "public_id" => $filename,
            "use_filename" => TRUE
        ])->getSecurePath();

        CertificateLicense::create([
            'image' => $response,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate License Added Successfully!'
        ], 200);
    }

    public function admin_certificate_license_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'cl_id' => ['required', 'numeric', 'exists:certificate_licenses,id'],
            'image' => 'nullable|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $certificateLicense = CertificateLicense::find($request->cl_id);

        if (request()->hasFile('image')) {

            $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $response = cloudinary()->uploadFile($request->image->getRealPath(),
            [
                'folder' => config('app.name').'/certificateLicenses',
                "public_id" => $filename,
                "use_filename" => TRUE
            ])->getSecurePath();

            if($certificateLicense->image)
            {
                $token = explode('/', $certificateLicense->image);
                $token2 = explode('.', $token[sizeof($token)-1]);
                cloudinary()->destroy(config('app.name').'/certificateLicenses/'.$token2[0]);
            }
        }

        $certificateLicense->update([
            'image' => $response ?? $certificateLicense->image
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificate License updated successfully!'
        ], 200);
    }

    public function admin_certificate_license_delete(Request $request)
    {
        $certificateLicense = CertificateLicense::find($request->cl_id);

        if (!$certificateLicense) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate License not found.',
            ], 401);
        }

        if($certificateLicense->image)
        {
            $token = explode('/', $certificateLicense->image);
            $token2 = explode('.', $token[sizeof($token)-1]);
            cloudinary()->destroy(config('app.name').'/certificateLicenses/'.$token2[0]);
        }

        $certificateLicense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate License deleted successfully.',
        ], 200);
    }


    public function admin_certifications()
    {
        $ertifications = Certification::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $ertifications
        ], 200);
    }

    public function admin_certification_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'description' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        Certification::create([
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certification Added Successfully!'
        ], 200);
    }

    public function admin_certification_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'certification_id' => ['required', 'numeric', 'exists:certifications,id'],
            'description' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $certification = Certification::find($request->certification_id);

        $certification->update([
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certification updated successfully!'
        ], 200);
    }

    public function admin_certification_delete(Request $request)
    {
        $certification = Certification::find($request->certification_id);

        if (!$certification) {
            return response()->json([
                'success' => false,
                'message' => 'Certification not found.',
            ], 401);
        }

        $certification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certification deleted successfully.',
        ], 200);
    }


    public function admin_offices()
    {
        $offices = Office::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $offices
        ], 200);
    }

    public function admin_office(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'addresses' => 'required|array', // Expecting an array of addresses
            'addresses.*' => 'required|string|max:255', // Each address should be a string
            'phones' => 'nullable|array', // Array of phone numbers
            'phones.*' => 'nullable|string|max:255', // Each phone should be a string
            'emails' => 'nullable|array', // Array of email addresses
            'emails.*' => 'nullable|email|max:255', // Each email should be valid
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get validated data
        $data = $validator->validated();

        // Check if any record exists
        $existingOffice = Office::first();

        if ($existingOffice) {
            // Update the existing office record
            $existingOffice->update($data);
            $office = $existingOffice;
        } else {
            // Create a new office record
            $office = Office::create($data);
        }

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => $existingOffice ? 'Office record updated successfully!' : 'Office record created successfully!',
        ], 200);
    }


    public function admin_ads_banners()
    {
        $ads = AdsBanner::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $ads
        ], 200);
    }

    public function admin_ads_banner_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'image' => 'required|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
        $filename = pathinfo($file, PATHINFO_FILENAME);

        $response = cloudinary()->uploadFile($request->image->getRealPath(),
        [
            'folder' => config('app.name') . '/ads-banners',
            "public_id" => $filename,
            "use_filename" => TRUE
        ])->getSecurePath();

        AdsBanner::create([
            'image' => $response,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ads Banner Added Successfully!'
        ], 200);
    }

    public function admin_ads_banner_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'banner_id' => ['required', 'numeric', 'exists:ads_banners,id'],
            'image' => 'nullable|mimes:jpeg,png,jpg|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $banner = AdsBanner::find($request->banner_id);

        if (request()->hasFile('image')) {

            $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $response = cloudinary()->uploadFile($request->image->getRealPath(),
            [
                'folder' => config('app.name').'/ads-banners',
                "public_id" => $filename,
                "use_filename" => TRUE
            ])->getSecurePath();

            if($banner->image)
            {
                $token = explode('/', $banner->image);
                $token2 = explode('.', $token[sizeof($token)-1]);
                cloudinary()->destroy(config('app.name').'/ads-banners/'.$token2[0]);
            }
        }

        $banner->update([
            'image' => $response ?? $banner->image
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ads Banner updated successfully!'
        ], 200);
    }

    public function admin_ads_banner_delete(Request $request)
    {
        $banner = AdsBanner::find($request->banner_id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Ads Banner not found.',
            ], 401);
        }

        if($banner->image)
        {
            $token = explode('/', $banner->image);
            $token2 = explode('.', $token[sizeof($token)-1]);
            cloudinary()->destroy(config('app.name').'/ads-banners/'.$token2[0]);
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ads Banner deleted successfully.',
        ], 200);
    }

    public function admin_career_roles()
    {
        $roles = CareerRole::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ], 200);
    }

    public function admin_career_role_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'role' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        CareerRole::create([
            'role' => $request->role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Career Role Added Successfully!'
        ], 200);
    }

    public function admin_career_role_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'role_id' => ['required', 'numeric', 'exists:career_roles,id'],
            'role' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $careerRole = CareerRole::find($request->role_id);

        $careerRole->update([
            'role' => $request->role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Career Role updated successfully!'
        ], 200);
    }

    public function admin_career_role_delete(Request $request)
    {
        $careerRole = CareerRole::find($request->role_id);

        if (!$careerRole) {
            return response()->json([
                'success' => false,
                'message' => 'Career Role not found.',
            ], 401);
        }

        $careerRole->delete();

        return response()->json([
            'success' => true,
            'message' => 'Career Role deleted successfully.',
        ], 200);
    }


    public function admin_breadcrumbs()
    {
        $breadcrumb = Breadcrumb::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $breadcrumb
        ], 200);
    }

    public function admin_breadcrumb_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string'],
            'image' => 'required|mimes:jpeg,png,jpg|max:1024',
            'link' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
        $filename = pathinfo($file, PATHINFO_FILENAME);

        $response = cloudinary()->uploadFile($request->image->getRealPath(),
        [
            'folder' => config('app.name') . '/breadcrumb',
            "public_id" => $filename,
            "use_filename" => TRUE
        ])->getSecurePath();

        Breadcrumb::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $response,
            'link' => $request->link,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Breadcrumb Added Successfully!'
        ], 200);
    }

    public function admin_breadcrumb_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'breadcrumb_id' => ['required', 'numeric', 'exists:breadcrumbs,id'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string'],
            'image' => 'nullable|mimes:jpeg,png,jpg|max:1024',
            'link' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $breadcrumb = Breadcrumb::find($request->breadcrumb_id);

        if (request()->hasFile('image')) {

            $file = str_replace(' ', '', uniqid(5).'-'.$request->image->getClientOriginalName());
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $response = cloudinary()->uploadFile($request->image->getRealPath(),
            [
                'folder' => config('app.name').'/breadcrumb',
                "public_id" => $filename,
                "use_filename" => TRUE
            ])->getSecurePath();

            if($breadcrumb->image)
            {
                $token = explode('/', $breadcrumb->image);
                $token2 = explode('.', $token[sizeof($token)-1]);
                cloudinary()->destroy(config('app.name').'/breadcrumb/'.$token2[0]);
            }
        }

        $breadcrumb->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $response ?? $breadcrumb->image,
            'link' => $request->link
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully!'
        ], 200);
    }

    public function admin_breadcrumb_delete(Request $request)
    {
        $breadcrumb = Breadcrumb::find($request->breadcrumb_id);

        if (!$breadcrumb) {
            return response()->json([
                'success' => false,
                'message' => 'Breadcrumb not found.',
            ], 401);
        }

        if($breadcrumb->image)
        {
            $token = explode('/', $breadcrumb->image);
            $token2 = explode('.', $token[sizeof($token)-1]);
            cloudinary()->destroy(config('app.name').'/breadcrumb/'.$token2[0]);
        }

        $breadcrumb->delete();

        return response()->json([
            'success' => true,
            'message' => 'Breadcrumb deleted successfully.',
        ], 200);
    }


    public function admin_peoples()
    {
        $peoples = People::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $peoples
        ], 200);
    }

    public function admin_people_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        People::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle
        ]);

        return response()->json([
            'success' => true,
            'message' => 'People Added Successfully!'
        ], 200);
    }

    public function admin_people_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'people_id' => ['required', 'numeric', 'exists:people,id'],
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $people = People::find($request->people_id);

        $people->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle
        ]);

        return response()->json([
            'success' => true,
            'message' => 'People updated successfully!'
        ], 200);
    }

    public function admin_people_delete(Request $request)
    {
        $people = People::find($request->people_id);

        if (!$people) {
            return response()->json([
                'success' => false,
                'message' => 'People not found.',
            ], 401);
        }

        $people->delete();

        return response()->json([
            'success' => true,
            'message' => 'People deleted successfully.',
        ], 200);
    }


    public function admin_stories()
    {
        $stories = Story::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $stories
        ], 200);
    }

    public function admin_story_post(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        Story::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Story Added Successfully!'
        ], 200);
    }

    public function admin_story_update(Request $request)
    {
        //Validate Request
        $validator = Validator::make(request()->all(), [
            'story_id' => ['required', 'numeric', 'exists:stories,id'],
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ], 422);
        }

        $story = Story::find($request->story_id);

        $story->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Story updated successfully!'
        ], 200);
    }

    public function admin_story_delete(Request $request)
    {
        $story = Story::find($request->story_id);

        if (!$story) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found.',
            ], 401);
        }

        $story->delete();

        return response()->json([
            'success' => true,
            'message' => 'Story deleted successfully.',
        ], 200);
    }


    // Fetch Sub Admins
    public function fetchSubAdmins()
    {
        $subAdmins = User::where('role', '!=', 'super-admin')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $subAdmins], 200);
    }

    // Add Sub Admin
    public function addSubAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $password = Str::random(10);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'email_verified_at' => now(),
            'role' => $request->role,
        ]);

        /** Store information to include in mail in $data as an array */
        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
            'created_at' => now(),
        );

        /** Send message to the sub admin */
        Mail::send('emails.subAdminLoginDetailsNotification', $data, function ($m) use ($data) {
            $m->to($data['email'])->subject(config('app.name').' - Your Sub-Admin Account');
        });


        return response()->json(['success' => true, 'message' => 'Subadmin added successfully.'], 200);
    }

    // Update Sub Admin
    public function updateSubAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subadmin_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->subadmin_id,
            'role' => 'required|string'
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $subAdmin = User::findOrFail($request->subadmin_id);
        $subAdmin->update($request->only('name', 'email', 'role'));

        return response()->json(['success' => true, 'message' => 'Subadmin updated successfully.'], 200);
    }

    // Delete Sub Admin
    public function deleteSubAdmin(Request $request)
    {
        $subAdmin = User::findOrFail($request->subadmin_id);
        $subAdmin->delete();

        return response()->json(['success' => true, 'message' => 'Subadmin deleted successfully.'], 200);
    }

    // Toggle Sub-Admin Status
    public function toggleSubAdminStatus(Request $request)
    {
        $subAdmin = User::findOrFail($request->subadmin_id);
        $newStatus = $subAdmin->status === 'active' ? 'inactive' : 'active';
        $subAdmin->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'message' => "Sub-Admin {$newStatus} successfully."], 200);
    }

    // Resend Login Details
    public function resendLoginDetails(Request $request)
    {
        $subAdmin = User::findOrFail($request->subadmin_id);
        $newPassword = Str::random(10);
        $subAdmin->update(['password' => bcrypt($newPassword)]);

        /** Store information to include in mail in $data as an array */
        $data = array(
            'name' => $subAdmin->name,
            'email' => $subAdmin->email,
            'password' => $newPassword,
            'role' => $subAdmin->role,
            'created_at' => now(),
        );

        /** Send message to the sub admin */
        Mail::send('emails.subAdminLoginDetailsNotification', $data, function ($m) use ($data) {
            $m->to($data['email'])->subject(config('app.name').' - Your New Login Details');
        });


        return response()->json(['success' => true, 'message' => 'Login details resent successfully.'], 200);
    }
}
