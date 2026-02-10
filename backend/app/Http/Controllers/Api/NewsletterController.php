<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    /**
     * POST /api/newsletter/subscribe
     */
    public function subscribe(NewsletterSubscribeRequest $request): JsonResponse
    {
        try {
            $subscriber = NewsletterSubscriber::updateOrCreate(
                ['email' => $request->email],
                [
                    'status' => 'active',
                    'source' => 'website',
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null, // Reset if resubscribing
                ]
            );

            // TODO: Send welcome email with 10% discount code
            // Mail::to($subscriber->email)->send(new WelcomeNewsletter());

            return response()->json([
                'success' => true,
                'message' => 'Thanks for subscribing! Check your email for your 10% off code.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    /**
     * POST /api/newsletter/unsubscribe
     * GDPR Article 21 - Right to Object
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email address not found in our newsletter list.',
                ], 404);
            }

            $subscriber->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully unsubscribed from our newsletter.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
