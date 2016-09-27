<?php

namespace Hugomn\IuguBundle\Controller;

use Hugomn\IuguBundle\Event\IuguWebhookEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WebhooksController extends Controller {

    /**
     * @Route("/webhooks", name="hugomn_iugu_webhooks")
     * @Method({"POST"})
     */
    public function postWebhooksAction(Request $request) {
        $data = $request->request->all();
        if (array_key_exists('event', $data) && array_key_exists('data', $data)) {
            $webhookEvent = new IuguWebhookEvent($data['event'], $data['data'], $request);
            $this->get('event_dispatcher')->dispatch('hugomn_iugu.webhook', $webhookEvent);
        }
        $this->get("mail_manager")->sendAdminMessage("Iugu notification", "<pre>" . print_r($data, true) . "</pre>");
        return true;
    }
}
