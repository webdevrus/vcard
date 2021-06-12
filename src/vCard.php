<?php

namespace WebDevRus\vCard;

use WebDevRus\vCard\Exceptions\vCardException;

class vCard extends vCardAttributes
{
    /** @var array */
    public $vcard;

    /**
     * @param  string $first
     * @param  string $last
     * @param  null|string $patronymic
     * @return self
     */
    public function setName(string $lastName = '', string $firstName = '', ?string $additionalName = null): self
    {
        if (empty($lastName) && empty($firstName)) {
            throw new vCardException('$lastName or $firstName can\'t be empty');
        }

        $this->vcard[] = sprintf(self::NAME, $lastName, $firstName, !is_null($additionalName) ? ';' . $additionalName : '');

        return $this;
    }

    /**
     * @param  string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new vCardException('Invalid URL');
        }

        $this->vcard[] = sprintf(self::URL, $url);

        return $this;
    }

    /**
     * Set binary image
     * 
     * @param  string $image Path to image
     * @return self
     */
    public function setImage(string $image): self
    {
        $mime = file_exists($image)
            ? mime_content_type($image)
            : get_headers($image, true)['Content-Type'];

        $type = $this->getImageType($mime);
        $content = file_get_contents($image);

        if (!$content) {
            throw new vCardException('Image not reading');
        }

        $this->vcard[] = sprintf(self::IMAGE, $type, base64_encode($content));

        return $this;
    }

    /**
     * Set image by URL
     * 
     * @param  string $url
     * @return self
     */
    public function setImageUrl(string $url): self
    {
        $this->vcard[] = sprintf(self::IMAGE_URL, $url);

        return $this;
    }

    /**
     * @param  string $date YYYY-MM-DD
     * @return self
     */
    public function setBirthday(string $date): self
    {
        if (!preg_match('/\d{4}-\d{2}-\d{2}/', $date)) {
            throw new vCardException('Invalid date format. Please use YYYY-MM-DD format.');
        }

        $this->vcard[] = sprintf(self::BDAY, date('Y-m-d', strtotime($date)));

        return $this;
    }

    /**
     * @param  string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new vCardException('Invalid E-Mail');
        }

        $this->vcard[] = sprintf(self::EMAIL, $email);

        return $this;
    }

    /**
     * @param  string $nickname
     * @return self
     */
    public function setNickname(string $nickname): self
    {
        $this->vcard[] = sprintf(self::NICKNAME, $nickname);

        return $this;
    }

    /**
     * @param  string $phone
     * @param  mixed $types
     * @return self
     */
    public function setPhone(string $phone, ...$types): self
    {
        if (preg_match('/[^0-9]/', $phone)) {
            throw new vCardException('Invalid phone');
        }

        if (!empty($types)) {
            $type = implode(',', $types);
        }

        $this->vcard[] = sprintf(self::PHONE, $type ?? 'cell,msg', $phone);

        return $this;
    }

    /**
     * @param  string $other
     * @return self
     */
    public function setOther(string $other): self
    {
        $this->vcard[] = $other;

        return $this;
    }

    /**
     * Get content vCard
     * 
     * @return string
     */
    public function getContent(): string
    {
        if (empty($this->vcard)) {
            throw new vCardException('Fields can\'t be empty');
        }

        array_push($this->vcard, 'END:VCARD');
        array_unshift($this->vcard, 'BEGIN:VCARD', 'VERSION:3.0');

        return implode("\r\n", $this->vcard);
    }

    /**
     * Get content with headers for vCard
     * 
     * @param  string $filename
     * @return string
     */
    public function get(string $filename = 'vcard'): string
    {
        header('Content-Type: text/x-vcard');
        header('Content-Disposition: inline; filename="' . $filename . '.vcf"');

        return $this->getContent();
    }

    private function getImageType(string $mime): string
    {
        $mime = explode('/', $mime)[1];

        if (!in_array($mime, self::IMAGE_TYPES)) {
            throw new vCardException('Invalid image. Please use formats: ' . implode(', ', self::IMAGE_TYPES));
        }

        return strtoupper($mime);
    }
}
