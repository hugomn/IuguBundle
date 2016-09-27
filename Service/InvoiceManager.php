<?php

namespace Hugomn\IuguBundle\Service;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Listener\BasicAuthListener;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceManager {
    /**
     * cURL headers
     *
     * @var array
     */
    protected $httpHeaders;

    /**
     * Iugu timeout in seconds
     *
     * @var int
     */
    protected $timeout;
    protected $container;
    protected $em;
    protected $logger;
    protected $endpoint;
    protected $apiToken;

    public function __construct($container, EntityManager $em, $logger, $endpoint, $apiToken, $timeout = 180) {
        $this->container = $container;
        $this->em = $em;
        $this->logger = $logger;
        $this->endpoint = $endpoint;
        $this->apiToken = $apiToken;
        $this->setTimeout($timeout);
        $this->setHTTPHeader('Accept', 'application/json');
        $this->setHTTPHeader('Content-Type', 'application/json');
    }

    /**
     * Set cURL headers
     * @param string $name
     * @param string $value
     */
    public function setHTTPHeader($name, $value) {
        $this->httpHeaders[$name] = $value;
    }

    /**
     * Set Postmark timeout in seconds
     * @param int $timeout
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    /**
     * Create Paypal Payment
     * @param  TicketOrder $ticketOrder Order
     * @param  string $accessToken Access token
     * @return Array              PayPal payment array
     */
    public function create($email, $dueDate, $items = null, $payer = null, $payableWith = 'all') {
        $url = $this->endpoint . '/v1/invoices';
        $curl = new Curl();
        $curl->setTimeout($this->timeout);
        $browser = new Browser($curl);
        $data = array(
            'email' => $email,
            'due_date' => date_format(new \DateTime($dueDate), 'd/m/Y'),
            'items' => $items,
            'payer' => $payer,
            'payable_with' => $payableWith
        );
        $browser->addListener(new BasicAuthListener($this->apiToken, null));
        $response = $browser->post($url, $this->httpHeaders, json_encode($data));
        return $response->isSuccessful() ? $response->getContent() : false;
    }
}
