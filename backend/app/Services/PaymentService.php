<?php

namespace App\Services;

use App\Models\Order;

class PaymentService
{
    /**
     * Process payment for an order
     *
     * @param Order $order
     * @param string $paymentToken
     * @return array
     */
    public function processPayment(Order $order, string $paymentToken): array
    {
        try {
            if ($order->payment_method === 'stripe') {
                return $this->processStripePayment($order, $paymentToken);
            } elseif ($order->payment_method === 'mollie') {
                return $this->processMolliePayment($order, $paymentToken);
            } elseif ($order->payment_method === 'paypal') {
                return $this->processPayPalPayment($order, $paymentToken);
            }

            throw new \Exception('Unsupported payment method');
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'transaction_id' => null,
            ];
        }
    }

    /**
     * Process Stripe payment
     *
     * @param Order $order
     * @param string $paymentToken
     * @return array
     */
    private function processStripePayment(Order $order, string $paymentToken): array
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => round($order->total * 100), // Convert to cents
                'currency' => 'eur',
                'payment_method' => $paymentToken,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'description' => "Quickdraw Order #{$order->order_number}",
                'metadata' => [
                    'order_number' => $order->order_number,
                    'customer_email' => $order->customer_email,
                ],
            ]);

            return [
                'success' => $paymentIntent->status === 'succeeded',
                'transaction_id' => $paymentIntent->id,
                'message' => 'Payment processed successfully',
            ];
        } catch (\Stripe\Exception\CardException $e) {
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Card declined: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process Mollie payment
     *
     * @param Order $order
     * @param string $paymentToken
     * @return array
     */
    private function processMolliePayment(Order $order, string $paymentToken): array
    {
        // SECURITY: Mollie payment not yet implemented
        // Do NOT use in production until properly implemented
        throw new \Exception('Mollie payment processing is not yet available. Please use Stripe or contact support.');

        // TODO: Implement Mollie payment processing
        // Requires: composer require mollie/mollie-api-php
    }

    /**
     * Process PayPal payment
     *
     * @param Order $order
     * @param string $paymentToken
     * @return array
     */
    private function processPayPalPayment(Order $order, string $paymentToken): array
    {
        // SECURITY: PayPal payment not yet implemented
        // Do NOT use in production until properly implemented
        throw new \Exception('PayPal payment processing is not yet available. Please use Stripe or contact support.');

        // TODO: Implement PayPal payment processing
        // Requires: composer require paypal/rest-api-sdk-php
    }
}
