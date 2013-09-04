<?php
/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Entity;

use Sonata\PageBundle\Entity\BaseSite as BaseSite;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Site extends BaseSite
{

    /**
     * @var integer $id
     */
    protected $id;
    
    /**
     *
     * @var type string
     */
    private $context;

    /**
     *
     * @var type string
     */
    private $twitterUrl;

    /**
     *
     * @var type string
     */
    private $facebookUrl;

    private $gacode;

    private $trip_advisor_key;

    /**
     * @param mixed $trip_advisor_key
     */
    public function setTripAdvisorKey($trip_advisor_key)
    {
        $this->trip_advisor_key = $trip_advisor_key;
    }

    /**
     * @return mixed
     */
    public function getTripAdvisorKey()
    {
        return $this->trip_advisor_key;
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @param mixed $gacode
     */
    public function setGacode($gacode)
    {
        $this->gacode = $gacode;
    }

    /**
     * @return mixed
     */
    public function getGacode()
    {
        return $this->gacode;
    }

    public function getFacebookUrl()
    {
        return $this->facebookUrl;
    }

    public function setFacebookUrl($facebookUrl)
    {
        $this->facebookUrl = $facebookUrl;
    }

    public function getTwitterUrl()
    {
        return $this->twitterUrl;
    }

    public function setTwitterUrl($twitterUrl)
    {
        $this->twitterUrl = $twitterUrl;
    }
    
    
    /**
     * @var string
     */
    private $checkout_confirmation_action;


    /**
     * Set checkout_confirmation_action
     *
     * @param string $checkoutConfirmationAction
     * @return Site
     */
    public function setCheckoutConfirmationAction($checkoutConfirmationAction)
    {
        $this->checkout_confirmation_action = $checkoutConfirmationAction;
    
        return $this;
    }

    /**
     * Get checkout_confirmation_action
     *
     * @return string 
     */
    public function getCheckoutConfirmationAction()
    {
        return $this->checkout_confirmation_action;
    }

    /**
     *
     * @var type string
     */
    private $fax;

    /**
     * @param \Application\Sonata\PageBundle\Entity\type $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\type
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \Application\Sonata\PageBundle\Entity\type $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\type
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param \Application\Sonata\PageBundle\Entity\type $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\type
     */
    public function getWeb()
    {
        return $this->web;
    }
    /**
     *
     * @var type string
     */
    private $web;
    /**
     *
     * @var type string
     */
    private $address;


    /**
     *
     * @var type string
     */
    private $phone;

    /**
     * @param \Application\Sonata\PageBundle\Entity\type $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\type
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     *
     * @var type string
     */
    private $contactBody;

    /**
     * @param \Application\Sonata\PageBundle\Entity\type $contactBody
     */
    public function setContactBody($contactBody)
    {
        $this->contactBody = $contactBody;
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\type
     */
    public function getContactBody()
    {
        return $this->contactBody;
    }

    /**
     * @param \Application\Sonata\PageBundle\Entity\type $termsAndConditions
     */
    public function setTermsAndConditions($termsAndConditions)
    {
        $this->termsAndConditions = $termsAndConditions;
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\type
     */
    public function getTermsAndConditions()
    {
        return $this->termsAndConditions;
    }

    /**
     *
     * @var type string
     */
    private $termsAndConditions;

    private $twitterAccesstoken;

    /**
     * @param mixed $twitterAccesstoken
     */
    public function setTwitterAccesstoken($twitterAccesstoken)
    {
        $this->twitterAccesstoken = $twitterAccesstoken;
    }

    /**
     * @return mixed
     */
    public function getTwitterAccesstoken()
    {
        return $this->twitterAccesstoken;
    }

    private $twitterAccesstokenSecret;
    /**
     * @param mixed $twitterAccesstokenSecret
     */
    public function setTwitterAccesstokenSecret($twitterAccesstokenSecret)
    {
        $this->twitterAccesstokenSecret = $twitterAccesstokenSecret;
    }

    /**
     * @return mixed
     */
    public function getTwitterAccesstokenSecret()
    {
        return $this->twitterAccesstokenSecret;
    }

    private $twitterConsumerKey;

    /**
     * @param mixed $twitterConsumerKey
     */
    public function setTwitterConsumerKey($twitterConsumerKey)
    {
        $this->twitterConsumerKey = $twitterConsumerKey;
    }

    /**
     * @return mixed
     */
    public function getTwitterConsumerKey()
    {
        return $this->twitterConsumerKey;
    }

    private $twitterConsumerSecret;

    /**
     * @param mixed $twitterConsumerSecret
     */
    public function setTwitterConsumerSecret($twitterConsumerSecret)
    {
        $this->twitterConsumerSecret = $twitterConsumerSecret;
    }

    /**
     * @return mixed
     */
    public function getTwitterConsumerSecret()
    {
        return $this->twitterConsumerSecret;
    }

    private $twitterScreenName;

    /**
     * @param mixed $twitterScreenName
     */
    public function setTwitterScreenName($twitterScreenName)
    {
        $this->twitterScreenName = $twitterScreenName;
    }

    /**
     * @return mixed
     */
    public function getTwitterScreenName()
    {
        return $this->twitterScreenName;
    }

    private $backgroundImage;

    /**
     * @param mixed $backgroundImage
     */
    public function setBackgroundImage($backgroundImage)
    {
        $this->backgroundImage = $backgroundImage;
    }

    /**
     * @return mixed
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }


}