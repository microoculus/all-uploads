<?php

return [
    /*
     * The default key used for all file encryption / decryption
     * This package will look for a FILE_VAULT_KEY in your env file
     * If no FILE_VAULT_KEY is found, then it will use your Laravel APP_KEY
     */
    'uploads_location_path' => "uploads/all",

    /*
     * The cipher used for encryption.
     * Supported options are AES-128-CBC and AES-256-CBC
	 * public, storage
     */
    'uploads_driver' => 'public',

    /*
     * The Storage disk used by default to locate your files. 
     * local
     * app_public
     */
    'disk' => 'local',
];
