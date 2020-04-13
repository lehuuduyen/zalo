<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V1\Service;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class VerificationCheckOptions {
    /**
     * @param string $to To phone number
     * @param string $verificationSid A SID that uniquely identifies this
     *                                Verification Check
     * @param string $amount Amount of the associated PSD2 compliant transaction.
     * @param string $payee Payee of the associated PSD2 compliant transaction.
     * @return CreateVerificationCheckOptions Options builder
     */
    public static function create($to = Values::NONE, $verificationSid = Values::NONE, $amount = Values::NONE, $payee = Values::NONE) {
        return new CreateVerificationCheckOptions($to, $verificationSid, $amount, $payee);
    }
}

class CreateVerificationCheckOptions extends Options {
    /**
     * @param string $to To phone number
     * @param string $verificationSid A SID that uniquely identifies this
     *                                Verification Check
     * @param string $amount Amount of the associated PSD2 compliant transaction.
     * @param string $payee Payee of the associated PSD2 compliant transaction.
     */
    public function __construct($to = Values::NONE, $verificationSid = Values::NONE, $amount = Values::NONE, $payee = Values::NONE) {
        $this->options['to'] = $to;
        $this->options['verificationSid'] = $verificationSid;
        $this->options['amount'] = $amount;
        $this->options['payee'] = $payee;
    }

    /**
     * The To phone number of the phone being verified
     * 
     * @param string $to To phone number
     * @return $this Fluent Builder
     */
    public function setTo($to) {
        $this->options['to'] = $to;
        return $this;
    }

    /**
     * A SID that uniquely identifies this Verification Check, either this parameter or the To phone number must be specified
     * 
     * @param string $verificationSid A SID that uniquely identifies this
     *                                Verification Check
     * @return $this Fluent Builder
     */
    public function setVerificationSid($verificationSid) {
        $this->options['verificationSid'] = $verificationSid;
        return $this;
    }

    /**
     * Amount of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * 
     * @param string $amount Amount of the associated PSD2 compliant transaction.
     * @return $this Fluent Builder
     */
    public function setAmount($amount) {
        $this->options['amount'] = $amount;
        return $this;
    }

    /**
     * Payee of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * 
     * @param string $payee Payee of the associated PSD2 compliant transaction.
     * @return $this Fluent Builder
     */
    public function setPayee($payee) {
        $this->options['payee'] = $payee;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Verify.V1.CreateVerificationCheckOptions ' . implode(' ', $options) . ']';
    }
}