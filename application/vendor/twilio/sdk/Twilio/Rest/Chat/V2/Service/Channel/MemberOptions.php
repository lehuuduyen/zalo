<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Chat\V2\Service\Channel;

use Twilio\Options;
use Twilio\Values;

abstract class MemberOptions {
    /**
     * @param string $roleSid The role to be assigned to this member. Defaults to
     *                        the roles specified on the Service.
     * @param integer $lastConsumedMessageIndex Field used to specify the last
     *                                          consumed Message index for the
     *                                          Channel for this Member.  Should
     *                                          only be used when recreating a
     *                                          Member from a backup/separate
     *                                          source.
     * @param \DateTime $lastConsumptionTimestamp ISO8601 time indicating the last
     *                                            datetime the Member consumed a
     *                                            Message in the Channel.  Should
     *                                            only be used when recreating a
     *                                            Member from a backup/separate
     *                                            source
     * @param \DateTime $dateCreated The ISO8601 time specifying the datetime the
     *                               Members should be set as being created.  Will
     *                               be set to the current time by the Chat service
     *                               if not specified.  Note that this should only
     *                               be used in cases where a Member is being
     *                               recreated from a backup/separate source
     * @param \DateTime $dateUpdated The ISO8601 time specifying the datetime the
     *                               Member should be set as having been last
     *                               updated.  Will be set to the null by the Chat
     *                               service if not specified.  Note that this
     *                               should only be used in cases where a Member is
     *                               being recreated from a backup/separate source 
     *                               and where a Member was previously updated.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return CreateMemberOptions Options builder
     */
    public static function create($roleSid = Values::NONE, $lastConsumedMessageIndex = Values::NONE, $lastConsumptionTimestamp = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $attributes = Values::NONE) {
        return new CreateMemberOptions($roleSid, $lastConsumedMessageIndex, $lastConsumptionTimestamp, $dateCreated, $dateUpdated, $attributes);
    }

    /**
     * @param string $identity A unique string identifier for this User in this
     *                         Service. See the access tokens docs for more details.
     * @return ReadMemberOptions Options builder
     */
    public static function read($identity = Values::NONE) {
        return new ReadMemberOptions($identity);
    }

    /**
     * @param string $roleSid The role to be assigned to this member.
     * @param integer $lastConsumedMessageIndex Field used to specify the last
     *                                          consumed Message index for the
     *                                          Channel for this Member.
     * @param \DateTime $lastConsumptionTimestamp ISO8601 time indicating the last
     *                                            datetime the Member consumed a
     *                                            Message in the Channel.
     * @param \DateTime $dateCreated The ISO8601 time specifying the datetime the
     *                               Members should be set as being created.
     * @param \DateTime $dateUpdated The ISO8601 time specifying the datetime the
     *                               Member should be set as having been last
     *                               updated.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return UpdateMemberOptions Options builder
     */
    public static function update($roleSid = Values::NONE, $lastConsumedMessageIndex = Values::NONE, $lastConsumptionTimestamp = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $attributes = Values::NONE) {
        return new UpdateMemberOptions($roleSid, $lastConsumedMessageIndex, $lastConsumptionTimestamp, $dateCreated, $dateUpdated, $attributes);
    }
}

class CreateMemberOptions extends Options {
    /**
     * @param string $roleSid The role to be assigned to this member. Defaults to
     *                        the roles specified on the Service.
     * @param integer $lastConsumedMessageIndex Field used to specify the last
     *                                          consumed Message index for the
     *                                          Channel for this Member.  Should
     *                                          only be used when recreating a
     *                                          Member from a backup/separate
     *                                          source.
     * @param \DateTime $lastConsumptionTimestamp ISO8601 time indicating the last
     *                                            datetime the Member consumed a
     *                                            Message in the Channel.  Should
     *                                            only be used when recreating a
     *                                            Member from a backup/separate
     *                                            source
     * @param \DateTime $dateCreated The ISO8601 time specifying the datetime the
     *                               Members should be set as being created.  Will
     *                               be set to the current time by the Chat service
     *                               if not specified.  Note that this should only
     *                               be used in cases where a Member is being
     *                               recreated from a backup/separate source
     * @param \DateTime $dateUpdated The ISO8601 time specifying the datetime the
     *                               Member should be set as having been last
     *                               updated.  Will be set to the null by the Chat
     *                               service if not specified.  Note that this
     *                               should only be used in cases where a Member is
     *                               being recreated from a backup/separate source 
     *                               and where a Member was previously updated.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     */
    public function __construct($roleSid = Values::NONE, $lastConsumedMessageIndex = Values::NONE, $lastConsumptionTimestamp = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $attributes = Values::NONE) {
        $this->options['roleSid'] = $roleSid;
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        $this->options['lastConsumptionTimestamp'] = $lastConsumptionTimestamp;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
        $this->options['attributes'] = $attributes;
    }

    /**
     * The role to be assigned to this member. Defaults to the roles specified on the [Service](https://www.twilio.com/docs/chat/api/services).
     * 
     * @param string $roleSid The role to be assigned to this member. Defaults to
     *                        the roles specified on the Service.
     * @return $this Fluent Builder
     */
    public function setRoleSid($roleSid) {
        $this->options['roleSid'] = $roleSid;
        return $this;
    }

    /**
     * Field used to specify the last consumed Message index for the Channel for this Member.  Should only be used when recreating a Member from a backup/separate source.
     * 
     * @param integer $lastConsumedMessageIndex Field used to specify the last
     *                                          consumed Message index for the
     *                                          Channel for this Member.  Should
     *                                          only be used when recreating a
     *                                          Member from a backup/separate
     *                                          source.
     * @return $this Fluent Builder
     */
    public function setLastConsumedMessageIndex($lastConsumedMessageIndex) {
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        return $this;
    }

    /**
     * ISO8601 time indicating the last datetime the Member consumed a Message in the Channel.  Should only be used when recreating a Member from a backup/separate source
     * 
     * @param \DateTime $lastConsumptionTimestamp ISO8601 time indicating the last
     *                                            datetime the Member consumed a
     *                                            Message in the Channel.  Should
     *                                            only be used when recreating a
     *                                            Member from a backup/separate
     *                                            source
     * @return $this Fluent Builder
     */
    public function setLastConsumptionTimestamp($lastConsumptionTimestamp) {
        $this->options['lastConsumptionTimestamp'] = $lastConsumptionTimestamp;
        return $this;
    }

    /**
     * The ISO8601 time specifying the datetime the Members should be set as being created.  Will be set to the current time by the Chat service if not specified.  Note that this should only be used in cases where a Member is being recreated from a backup/separate source
     * 
     * @param \DateTime $dateCreated The ISO8601 time specifying the datetime the
     *                               Members should be set as being created.  Will
     *                               be set to the current time by the Chat service
     *                               if not specified.  Note that this should only
     *                               be used in cases where a Member is being
     *                               recreated from a backup/separate source
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated) {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The ISO8601 time specifying the datetime the Member should be set as having been last updated.  Will be set to the `null` by the Chat service if not specified.  Note that this should only be used in cases where a Member is being recreated from a backup/separate source  and where a Member was previously updated.
     * 
     * @param \DateTime $dateUpdated The ISO8601 time specifying the datetime the
     *                               Member should be set as having been last
     *                               updated.  Will be set to the null by the Chat
     *                               service if not specified.  Note that this
     *                               should only be used in cases where a Member is
     *                               being recreated from a backup/separate source 
     *                               and where a Member was previously updated.
     * @return $this Fluent Builder
     */
    public function setDateUpdated($dateUpdated) {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }

    /**
     * An optional string metadata field you can use to store any data you wish. The string value must contain structurally valid JSON if specified.  **Note** that if the attributes are not set "{}" will be returned.
     * 
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return $this Fluent Builder
     */
    public function setAttributes($attributes) {
        $this->options['attributes'] = $attributes;
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
        return '[Twilio.Chat.V2.CreateMemberOptions ' . implode(' ', $options) . ']';
    }
}

class ReadMemberOptions extends Options {
    /**
     * @param string $identity A unique string identifier for this User in this
     *                         Service. See the access tokens docs for more details.
     */
    public function __construct($identity = Values::NONE) {
        $this->options['identity'] = $identity;
    }

    /**
     * A unique string identifier for this [User](https://www.twilio.com/docs/api/chat/rest/users) in this [Service](https://www.twilio.com/docs/api/chat/rest/services). See the [access tokens](https://www.twilio.com/docs/api/chat/guides/create-tokens) docs for more details.
     * 
     * @param string $identity A unique string identifier for this User in this
     *                         Service. See the access tokens docs for more details.
     * @return $this Fluent Builder
     */
    public function setIdentity($identity) {
        $this->options['identity'] = $identity;
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
        return '[Twilio.Chat.V2.ReadMemberOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateMemberOptions extends Options {
    /**
     * @param string $roleSid The role to be assigned to this member.
     * @param integer $lastConsumedMessageIndex Field used to specify the last
     *                                          consumed Message index for the
     *                                          Channel for this Member.
     * @param \DateTime $lastConsumptionTimestamp ISO8601 time indicating the last
     *                                            datetime the Member consumed a
     *                                            Message in the Channel.
     * @param \DateTime $dateCreated The ISO8601 time specifying the datetime the
     *                               Members should be set as being created.
     * @param \DateTime $dateUpdated The ISO8601 time specifying the datetime the
     *                               Member should be set as having been last
     *                               updated.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     */
    public function __construct($roleSid = Values::NONE, $lastConsumedMessageIndex = Values::NONE, $lastConsumptionTimestamp = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE, $attributes = Values::NONE) {
        $this->options['roleSid'] = $roleSid;
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        $this->options['lastConsumptionTimestamp'] = $lastConsumptionTimestamp;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
        $this->options['attributes'] = $attributes;
    }

    /**
     * The role to be assigned to this member. Defaults to the roles specified on the [Service](https://www.twilio.com/docs/chat/api/services).
     * 
     * @param string $roleSid The role to be assigned to this member.
     * @return $this Fluent Builder
     */
    public function setRoleSid($roleSid) {
        $this->options['roleSid'] = $roleSid;
        return $this;
    }

    /**
     * Field used to specify the last consumed Message index for the Channel for this Member.  Should only be used when recreating a Member from a backup/separate source.
     * 
     * @param integer $lastConsumedMessageIndex Field used to specify the last
     *                                          consumed Message index for the
     *                                          Channel for this Member.
     * @return $this Fluent Builder
     */
    public function setLastConsumedMessageIndex($lastConsumedMessageIndex) {
        $this->options['lastConsumedMessageIndex'] = $lastConsumedMessageIndex;
        return $this;
    }

    /**
     * ISO8601 time indicating the last datetime the Member consumed a Message in the Channel.  Should only be used when recreating a Member from a backup/separate source
     * 
     * @param \DateTime $lastConsumptionTimestamp ISO8601 time indicating the last
     *                                            datetime the Member consumed a
     *                                            Message in the Channel.
     * @return $this Fluent Builder
     */
    public function setLastConsumptionTimestamp($lastConsumptionTimestamp) {
        $this->options['lastConsumptionTimestamp'] = $lastConsumptionTimestamp;
        return $this;
    }

    /**
     * The ISO8601 time specifying the datetime the Members should be set as being created.  Will be set to the current time by the Chat service if not specified.  Note that this should only be used in cases where a Member is being recreated from a backup/separate source
     * 
     * @param \DateTime $dateCreated The ISO8601 time specifying the datetime the
     *                               Members should be set as being created.
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated) {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The ISO8601 time specifying the datetime the Member should be set as having been last updated.  Will be set to the `null` by the Chat service if not specified.  Note that this should only be used in cases where a Member is being recreated from a backup/separate source  and where a Member was previously updated.
     * 
     * @param \DateTime $dateUpdated The ISO8601 time specifying the datetime the
     *                               Member should be set as having been last
     *                               updated.
     * @return $this Fluent Builder
     */
    public function setDateUpdated($dateUpdated) {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }

    /**
     * An optional string metadata field you can use to store any data you wish. The string value must contain structurally valid JSON if specified.  **Note** that if the attributes are not set "{}" will be returned.
     * 
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return $this Fluent Builder
     */
    public function setAttributes($attributes) {
        $this->options['attributes'] = $attributes;
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
        return '[Twilio.Chat.V2.UpdateMemberOptions ' . implode(' ', $options) . ']';
    }
}