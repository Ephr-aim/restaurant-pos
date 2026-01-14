<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        // Add M-Pesa payment methods to config file
        if (function_exists('writeConfig')) {
            // Get existing payment methods
            $currentMethods = readConfig('payment_methods') ?: 'cash';
            
            // Add M-Pesa methods if not already there
            $methodsArray = explode(',', $currentMethods);
            $newMethods = ['mpesa_cash', 'mpesa_stk'];
            
            foreach ($newMethods as $method) {
                if (!in_array($method, $methodsArray)) {
                    $methodsArray[] = $method;
                }
            }
            
            writeConfig('payment_methods', implode(',', $methodsArray));
            writeConfig('mpesa_stk_enabled', '1');
            writeConfig('mpesa_cash_enabled', '1');
            
            echo "M-Pesa payment methods added to configuration.\n";
        }
    }

    public function down()
    {
        // Remove M-Pesa methods (optional - you may not want to remove them)
        if (function_exists('writeConfig')) {
            $currentMethods = readConfig('payment_methods') ?: 'cash';
            $methodsArray = explode(',', $currentMethods);
            $methodsArray = array_filter($methodsArray, function($method) {
                return !in_array($method, ['mpesa_cash', 'mpesa_stk']);
            });
            
            writeConfig('payment_methods', implode(',', $methodsArray));
            writeConfig('mpesa_stk_enabled', '0');
            writeConfig('mpesa_cash_enabled', '0');
            
            echo "M-Pesa payment methods removed from configuration.\n";
        }
    }
};
