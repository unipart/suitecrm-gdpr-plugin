<?php

class UP_GDPR_4_PP extends Basic
{
    /**
     * @return string
     */
    public function generateUuid() : string
    {
        return uniqid(md5(time() . $this->name));
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        global $sugar_config;
        return $sugar_config['site_url'] . '/index.php?entryPoint=PrivacyPreferencesEntryPoint&uuid=' . $this->uuid;
    }

}
