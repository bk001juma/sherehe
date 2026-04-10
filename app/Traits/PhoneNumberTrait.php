<?php

namespace App\Traits;

class PhoneNumberTrait
{
    private $phone = null;

    /**
     * Get the Ip Address of the user.
     *
     * @return string
     */
    public function clearNumber($number): string
    {
        $new_no = preg_replace('/\s+/', '', $number);
        $new_no = preg_replace('/-/', '', $new_no);
        $new_no = preg_replace('/\)/', '', $new_no);
        $new_no = preg_replace('/\(/', '', $new_no);

        if (strpos($new_no, '0') === 0) {
            $new_no = preg_replace('/^0/', '+255', $new_no);
        }
        if(strpos($new_no, '255') === 0) {
            $new_no = preg_replace('/^255/', '+255', $new_no);
        }
        if(strpos($new_no, '6') === 0) {
            $new_no = preg_replace('/^6/', '+2556', $new_no);
        }
        if(strpos($new_no, '7') === 0) {
            $new_no = preg_replace('/^7/', '+2557', $new_no);
        }
        return $new_no;

        return $phone;
    }
}
