## Notes


https://www.paybox-services.com/


L'environnement de test s'active dans le backoffice monético (15 jours max)
L'url de l'interface de retour (IPN) est à renseignée aussi dans le backoffice.


```
### fichier .en

PAYBOX_TEST=1
PAYBOX_CLE_MAC=xxxxxxxxxxxx
PAYBOX_TPE=xxxxx
PAYBOX_CODE_SOCIETE=xxxxx
```


## Appel de la page de paiement


```

$billing = new OrderContextBilling($reservation->adresse, $reservation->ville, $reservation->cp, $reservation->pays->alpha2);
$context = new OrderContext($billing);
$payment_request = new PaymentRequest($reservation->reference, $reservation->total, $context, $reservation->email);
$payment_request->setUrlRetourOk(route('reservation.confirmation'));
$payment_request->setUrlRetourErreur(route('paiement.refuse'));


return redirect()->away($payment_request->link());

```



La route IPN doit être en GET et en POST
Pour passer en production, le serveur commerçant doit avoir renvoyé un accusé
de réception avec un sceau validé pour les trois derniers tests

