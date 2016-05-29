<?php

require_once "aws.config.php";
require_once "../AWS/aws-autoloader.php";

class AWS {

    /**
     * Creates a new S3 object
     *
     * @var Object
     */
    private $_s3;

    /**
     * AWS class constructor. Creates the S3 object
     */
    public function __construct() {
        $this -> _s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region' => AWS_REGION,
            'credentials' => array(
                'key' => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET
            )
        ]);
    }

    /**
     * Returns TRUE if file is deleted on S3.
     * Cross-dependency: File key is project ID
     *
     * @param int $pid  The project ID/file key (without extension)
     * @return bool     Whether file is successfully deleted
     */
    public function delete_object($pid) {
        $pid = intval($pid);
        $result = $this-> _s3 -> deleteObject(array(
            'Bucket' => AWS_BUCKET_NAME,
            'Key' => $pid.AWS_PICTURE_EXT
        ));
        return $result['DeleteMarker'];
    }

    /**
     * Returns TRUE if file is uploaded on S3.
     * !!!Should check if object already exists on S# EXTERNALLY. Call this function AFTER removing existing object.
     * Cross-dependency: File key is project ID, file upload name is "pic"
     *
     * @param int $pid  The project ID/file key (without extension)
     * @return bool     Whether file is successfully uploaded
     */
    public function upload_object($pid) {
        if (!isset($_FILES['pic'])) {
            echo "No file uploaded.";
            return FALSE;
        }
        $pid = intval($pid);
        $filename = $_FILES['pic']['tmp_name'];
        $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
        $img = NULL;

        if ($ext == "jpg" || $ext == "jpeg") {
            $img = imagecreatefromjpeg($filename);
        } elseif ($ext == "png") {
            $img = imagecreatefrompng($filename);
        } elseif ($ext == "gif") {
            $img = imagecreatefromgif($filename);
        } else {
            echo "File type not supported.";
            return FALSE;
        }

        if ($img !== NULL) {
            $width = imagesx($img);
            $height = imagesy($img);
            $upload = imagecreatetruecolor($width, $height);
            imagecopy($upload, $img, 0, 0, 0, 0, $width, $height);

            //Storage on AWS S3
            $temp = tempnam(sys_get_temp_dir(), "TMP");
            imagejpeg($upload, $temp, 100);

            $result = $this->_s3->putObject(array(
                'Bucket' => AWS_BUCKET_NAME,
                'Key' => $pid.AWS_PICTURE_EXT,
                'SourceFile' => $temp
            ));
            return TRUE;
        }
        return FALSE;
    }






}