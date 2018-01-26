<?php

class Validator
{
    private $uploadErrors = [
      UPLOAD_ERR_INI_SIZE => 'Uploaded file exceeds size limit.',
      UPLOAD_ERR_FORM_SIZE => 'Uploaded file exceeds size limit.',
      UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
      UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
      UPLOAD_ERR_NO_TMP_DIR => 'Server error.',
      UPLOAD_ERR_CANT_WRITE => 'Server error.',
      UPLOAD_ERR_EXTENSION => 'Server error.'
    ];
    private $allowedTypes = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];
    private $allowedExtensions = ['png', 'jpg', 'gif'];
    public $errors = [];


    public function validateImage($file)
    {
        if (!$file) {
            $this->errors['image'][] = 'File not found';
        } else {
            if ($file['error']) {
                $this->errors['image'][] = $this->uploadErrors[$file['error']];
            } else {
                $detectedType=exif_imagetype($file["tmp_name"]);
                if (!in_array($detectedType, $this->allowedTypes)) {
                    $this->errors['image'][] = 'The type of this file is not allowed.';
                }

                $detectedExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
                if (!in_array($detectedExtension, $this->allowedExtensions)) {
                    $this->errors['image'][] = 'Files with this extension are not allowed.';
                }
            }
        }
    }

    public function validateTags($tags)
    {
        if (!$tags) {
            $this->errors['tags'][] = 'The tags field can not be empty.';
        } else {
            foreach ($tags as $tag) {
                if (!preg_match("/^[a-zA-Zа-яёА-ЯЁ0-9\-_]+$/u", $tag)) {
                    $this->errors['tags']['characters'] = 'The tags field contains invalid characters. Only letters and numbers are allowed.';
                }
                if (strlen($tag)>=255) {
                  $this->errors['tags']['len'] = 'Each tag must be no longer than 50 characters';
                }
            }
        }
    }

    public function getHtmlErrors()
    {
        foreach ($this->errors as $name => $group) {
            $result[$name]= '';
            foreach ($group as $error) {
                $result[$name] .= '<div class="form-control-feedback">'.$error.'</div>';
            }
        }
        return $result;
    }
}
