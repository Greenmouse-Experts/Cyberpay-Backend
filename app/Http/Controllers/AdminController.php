<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BlogPost;
use App\Models\Career;
use App\Models\CertificateLicense;
use App\Models\Certification;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\WhistleBlower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
}
