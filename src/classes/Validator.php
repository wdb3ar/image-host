<?php

class Validator
{
    private $allowedTypes = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];
    private $allowedExtensions = ['png', 'jpg', 'gif'];
    public $errors = [];


    public function validateImage($file)
    {
        if (!$file) {
            $this->errors['image'][] = 'File not found';
        } else {
            $detectedType=exif_imagetype($file["tmp_name"]);
            if (!in_array($detectedType, $this->allowedTypes)) {
                $this->errors['image'][] = 'The type of this file is not allowed.';
            }

            $detectedExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
            if (!in_array($detectedExtension, $this->allowedExtensions)) {
                $this->errors['image'][] = 'Files with this extension are not allowed.';
            }

            if (!empty($this->errors['image']) && $this->allowedExtensions) {
                $this->errors['image'][] = 'Allowed image formats: ' . implode(", ", $this->allowedExtensions) . '.';
            }
        }
    }

    public function getHtmlErrors()
    {
        foreach ($this->errors as $name => $group) {
            foreach ($group as $error) {
                $result[$name] .= '<div class="form-control-feedback">'.$error.'</div>';
            }
        }
        return $result;
    }
}
