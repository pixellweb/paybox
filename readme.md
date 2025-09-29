## Notes


```
### fichier .en

PAYBOX_TEST=true
PAYBOX_SITE=xxxxxxx
PAYBOX_RANG=xx
PAYBOX_ID=xxxxxxxxx
PAYBOX_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```


## Appel de la page de paiement


```

        $paybox = new PaymentRequest(
            $reservation->reference,
            $reservation->echeancier->count() ?  $reservation->echeancier->first()->montant : ($reservation->acompte ?? $reservation->total),
            $reservation->email,
            $reservation->prenom,
            $reservation->nom,
        );

        return redirect()->away($paybox->link());

```



