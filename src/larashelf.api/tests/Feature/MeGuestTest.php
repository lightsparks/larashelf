<?php


it('returns 401 for /api/me when guest', function () {
    $this->getJson('/api/me')->assertUnauthorized();
});
