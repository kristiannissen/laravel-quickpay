## Introduktion

Laravel QuickPay gør det muligt at gøre brug af QuickPay som betalings gateway in en Laravel baseret løsning.
Denne pakke gør brug af en række specifikke Laravel features så som:

* Events
* Collections
* Eloquent Models

Implementeringen dækker primært følgende QuickPay services

* Subscription
* Account
* Acquires
* Payments

## Eksempler

I det følgende kan du se eksempler på hvordan denne pakke kan anvendes.

### Oprettelse af payment
```
$service = new PaymentService();
$payment = $service->create([
            'order_id' => Str::random(10),
            'currency' => 'DKK',
        ]);
```
Payment->Create returnerer et Object som ...
