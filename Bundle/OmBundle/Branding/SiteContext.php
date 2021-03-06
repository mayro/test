<?php

namespace Adidas\Bundle\OmBundle\Branding;

/**
 * Represents the multisite context.
 *
 * This service is stateful, because it contains a "current" branding/locale.
 */
class SiteContext
{
    /**
     * @var Branding[]
     */
    private $brandings = array();

    /**
     * @var string
     */
    private $defaultBrandingName;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var Branding
     */
    private $currentBranding;

    /**
     * @var string
     */
    private $currentLocale;

    /**
     * Creates a new instance.
     *
     * @param Branding[] $brandings
     * @param string     $defaultBrandingName
     * @param string     $defaultLocale
     */
    public function __construct(array $brandings, $defaultBrandingName, $defaultLocale)
    {
        $this->brandings = $brandings;

        $this->defaultBrandingName = $defaultBrandingName;
        $this->defaultLocale   = $defaultLocale;
    }

    /**
     * Changes the current branding of site context.
     *
     * @param Branding $branding
     *
     * @return SiteContext
     */
    public function setCurrentBranding($branding)
    {
        $this->currentBranding = $branding;

        return $this;
    }

    /**
     * Changes the current locale.
     *
     * @param string $locale
     *
     * @return SiteContext
     */
    public function setCurrentLocale($locale)
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Returns name of the current branding.
     *
     * @return string
     */
    public function getCurrentBrandingName()
    {
        return $this->getCurrentBranding()->getName();
    }

    /**
     * Returns current branding.
     *
     * @return Branding
     */
    public function getCurrentBranding()
    {
        if (null === $this->currentBranding) {
            return $this->getBranding($this->defaultBrandingName);
        }

        return $this->currentBranding;
    }

    /**
     * Returns current locale.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        if (null === $this->currentLocale) {
            return $this->defaultLocale;
        }

        return $this->currentLocale;
    }

    /**
     * Returns a given branding.
     *
     * @param string $name
     *
     * @return Branding
     */
    public function getBranding($name)
    {
        if (array_key_exists($name, $this->brandings)) {
            return $this->brandings[$name];
        }

        throw new \InvalidArgumentException(sprintf('No branding named "%s".', $name));
    }

    /**
     * Returns brandings with a given locale.
     *
     * @param string $locale
     *
     * @return Branding[]
     */
    public function getBrandingsWithLocale($locale)
    {
        $result = array();
        foreach ($this->brandings as $branding) {
            if ($branding->hasLocale($locale)) {
                $result[] = $branding;
            }
        }

        return $result;
    }

    /**
     * Converts an array used in annotation to an associative array branding/locale.
     *
     * @return array
     */
    public function normalizePaths(array $paths)
    {
        $result = array();

        foreach ($paths as $key => $value) {
            // key is locale
            if (is_string($value)) {
                foreach ($this->getBrandingsWithLocale($key) as $branding) {
                    $result[$branding->getName()][$key] = $branding->prefixPath($key, $value);
                }
            }

            // key is branding
            if (is_array($value)) {
                foreach ($value as $locale => $path) {
                    $result[$key][$locale] = $this->getBranding($key)->prefixPath($locale, $path);
                }
            }
        }

        return $result;
    }

    /**
     * Returns value of an option.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return $this->getCurrentBranding()->getOption($this->getCurrentLocale(), $name, $default);
    }

    /**
     * Adds a new branding to the context.
     *
     * @param Branding $branding
     *
     * @return SiteContext
     */
    private function addBranding($branding)
    {
        $this->brandings[] = $branding;

        return $this;
    }
}
