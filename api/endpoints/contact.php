<?php
/**
 * De contactmail loopt uitsluitend via de sessiegebonden webroute /contact.
 * Deze historische JSON-route blijft als expliciete 410 bestaan zodat oude
 * clients geen generieke routerfout krijgen en bots geen tweede mailingang
 * kunnen gebruiken.
 */
class ContactAPI {
    public function __construct() {
    }

    public function handle($method, $segments) {
        sendApiError('Deze contactroute is niet meer beschikbaar. Gebruik het contactformulier.', 410);
    }
}
