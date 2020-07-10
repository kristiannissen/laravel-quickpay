<?php

dataset('cards', [
    {
        'card_approved': {
            'visa': '1000 0000 0000 0008'
        },
        'card_rejected': {
            'visa': '1000 0000 0000 0016'
        },
        'card_expired': {
            'visa': '1000 0000 0000 0024'
        },
        'capture_rejected': {
            'visa': '1000 0000 0000 0032'
        },
        'refund_rejected': {
            'visa': '1000 0000 0000 0040'
        },
        'cancel_rejected': {
            'visa': '1000 0000 0000 0057'
        },
        'recurring_rejected': {
            'visa': '1000 0000 0000 0065'
        },
        '3d_secure_required': {
            'visa': '1000 0000 0000 0073'
        },
        'authorize_once': {
            'visa': '1000 0000 0000 0081'
        },
        'delay_queue': {
            'visa': '1000 0000 0000 0099'
        }
    }
]);
