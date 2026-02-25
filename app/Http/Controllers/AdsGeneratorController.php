<?php
namespace app\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class AdsGeneratorController extends Controller
{
    public function index()
    {
        return view('Adsgenerator.index');
    }

    public function generateAd(Request $request)
    {
        Log::info('Generating ad with request data:', $request->all());

        try {
            // Validate user input
            $request->validate([
                'photo' => 'required|image|max:1024', // Max 1MB
                'overlay_text' => 'nullable|string|max:50',
                'watermark' => 'nullable|boolean',
            ]);

            Log::info('Validation successful');

            // Initialize base transformations to enhance image quality
            $transformations = [
                ['effect' => 'improve'], // Automatically enhance image quality
                ['quality' => 'auto:best'], // Optimize for the best clarity and size
            ];

            // Add a blurred background for the uploaded photo
            $transformations[] = [
                'effect' => 'blur:200', // Apply a strong blur
                'crop' => 'fill', // Ensure the background covers the full area
                'gravity' => 'auto', // Focus on the main subject for cropping
                'background' => 'grey', // Set a fallback color
            ];

            // Overlay the uploaded image in the center
            $transformations[] = [
                'width' => 'auto', // Maintain the original width of the uploaded image
                'crop' => 'scale', // Proportionally scale the image
                'gravity' => 'center', // Center it
            ];

            // Add overlay text if provided
            if (!empty($request->overlay_text)) {
                $transformations[] = [
                    'overlay' => [
                        'font_family' => 'verdana', // Ensure the font is uploaded to Cloudinary
                        'font_size' => 36,
                        'font_weight' => 'bold',
                        'text' => $request->overlay_text,
                        'color' => 'white',
                        'background' => 'rgba:0,0,0,0.5', // Semi-transparent background
                    ],
                    'gravity' => 'south', // Place text at the bottom
                    'y' => 50, // Add slight spacing
                ];
            }

            // Add watermark if enabled
            if (!empty($request->watermark)) {
                $transformations[] = [
                    'overlay' => 'your_watermark_image', // Replace with your watermark asset ID
                    'width' => 150, // Resize the watermark
                    'opacity' => 50, // Make it semi-transparent
                    'gravity' => 'south_east', // Position at the bottom-right corner
                    'x' => 20, // Add margin from the right
                    'y' => 20, // Add margin from the bottom
                ];
            }

            // Final transformations ensuring image focus without resizing
            $transformations[] = [
                'flags' => 'region_relative', // Apply effects relative to regions
                'gravity' => 'auto', // Ensure focus remains on the main subject
            ];

            // Debugging: Log the transformations
            Log::info('Transformations:', $transformations);

            // Upload the image to Cloudinary with transformations
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('photo')->getRealPath(),
                [
                    'folder' => 'ad_generator',
                    'transformation' => $transformations
                ]
            )->getSecurePath();

            Log::info('File uploaded successfully to Cloudinary');

            // Pass the uploaded image URL to the view
            return view('Adsgenerator.index', ['ad_image' => $uploadedFileUrl]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch validation errors and provide feedback to the user
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Catch other exceptions and log them
            Log::error('Error generating ad:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to generate ad: ' . $e->getMessage());
        }
    }
}
