<?php

// O status 200 indica que a requisição foi bem-sucedida e que o servidor retornou o conteúdo esperado.
// O status 302 indica um redirecionamento temporário.

describe('Testar GET das páginas', function () {

    test('Welcome deve responder com status 200', function () {
        $response = $this->get('/');
        expect($response->status())->toBe(200);

        //ou:
        //expect($this->get('/')->status())->toBe(200);
    });

    test('Perfil deve exigir autenticação', function () {
        $response = $this->get('/profile');
        expect($response->status())->toBe(302);
    });

    test('Página de termos deve responder com status 200', function () {
        $response = $this->get('/termos');
        expect($response->status())->toBe(200);
        expect($response)->content()->toContain("Termos e Condições");
    });

});
