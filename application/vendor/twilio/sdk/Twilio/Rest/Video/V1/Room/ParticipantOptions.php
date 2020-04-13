<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1\Room;

use Twilio\Options;
use Twilio\Values;

abstract class ParticipantOptions {
    /**
     * @param string $status Only show Participants with the given Status.
     * @param string $identity Only show Participants that connected to the Room
     *                         using the provided Identity.
     * @param \DateTime $dateCreatedAfter Only show Participants that started after
     *                                    this date, given as an UTC ISO 8601
     *                                    Timestamp.
     * @param \DateTime $dateCreatedBefore Only show Participants that started
     *                                     before this date, given as an UTC ISO
     *                                     8601 Timestamp.
     * @return ReadParticipantOptions Options builder
     */
    public static function read($status = Values::NONE, $identity = Values::NONE, $dateCreatedAfter = Values::NONE, $dateCreatedBefore = Values::NONE) {
        return new ReadParticipantOptions($status, $identity, $dateCreatedAfter, $dateCreatedBefore);
    }

    /**
     * @param string $status Set to disconnected to remove participant.
     * @return UpdateParticipantOptions Options builder
     */
    public static function update($status = Values::NONE) {
        return new UpdateParticipantOptions($status);
    }
}

class ReadParticipantOptions extends Options {
    /**
     * @param string $status Only show Participants with the given Status.
     * @param string $identity Only show Participants that connected to the Room
     *                         using the provided Identity.
     * @param \DateTime $dateCreatedAfter Only show Participants that started after
     *                                    this date, given as an UTC ISO 8601
     *                                    Timestamp.
     * @param \DateTime $dateCreatedBefore Only show Participants that started
     *                                     before this date, given as an UTC ISO
     *                                     8601 Timestamp.
     */
    public function __construct($status = Values::NONE, $identity = Values::NONE, $dateCreatedAfter = Values::NONE, $dateCreatedBefore = Values::NONE) {
        $this->options['status'] = $status;
        $this->options['identity'] = $identity;
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
    }

    /**
     * Only show Participants with the given Status.  For `in-progress` Rooms the default Status is `connected`, for `completed` Rooms only `disconnected` Participants are returned.
     * 
     * @param string $status Only show Participants with the given Status.
     * @return $this Fluent Builder
     */
    public function setStatus($status) {
        $this->options['status'] = $status;
        return $this;
    }

    /**
     * Only show Participants that connected to the Room using the provided Identity.
     * 
     * @param string $identity Only show Participants that connected to the Room
     *                         using the provided Identity.
     * @return $this Fluent Builder
     */
    public function setIdentity($identity) {
        $this->options['identity'] = $identity;
        return $this;
    }

    /**
     * Only show Participants that started after this date, given as an [UTC ISO 8601 Timestamp](http://en.wikipedia.org/wiki/ISO_8601#UTC).
     * 
     * @param \DateTime $dateCreatedAfter Only show Participants that started after
     *                                    this date, given as an UTC ISO 8601
     *                                    Timestamp.
     * @return $this Fluent Builder
     */
    public function setDateCreatedAfter($dateCreatedAfter) {
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        return $this;
    }

    /**
     * Only show Participants that started before this date, given as an [UTC ISO 8601 Timestamp](http://en.wikipedia.org/wiki/ISO_8601#UTC).
     * 
     * @param \DateTime $dateCreatedBefore Only show Participants that started
     *                                     before this date, given as an UTC ISO
     *                                     8601 Timestamp.
     * @return $this Fluent Builder
     */
    public function setDateCreatedBefore($dateCreatedBefore) {
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
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
        return '[Twilio.Video.V1.ReadParticipantOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateParticipantOptions extends Options {
    /**
     * @param string $status Set to disconnected to remove participant.
     */
    public function __construct($status = Values::NONE) {
        $this->options['status'] = $status;
    }

    /**
     * Set to `disconnected` to remove participant.
     * 
     * @param string $status Set to disconnected to remove participant.
     * @return $this Fluent Builder
     */
    public function setStatus($status) {
        $this->options['status'] = $status;
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
        return '[Twilio.Video.V1.UpdateParticipantOptions ' . implode(' ', $options) . ']';
    }
}