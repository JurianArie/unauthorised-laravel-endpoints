<?php

namespace JurianArie\UnauthorisedDetection\Tests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use JurianArie\UnauthorisedDetection\Detector;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\ControllerWithAuthorizationMiddleware;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\ControllerWithAuthorizeCall;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\ControllerWithAuthorizingFormRequest;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\ControllerWithGateCall;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\ControllerWithoutAuthorization;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\ControllerWithoutAuthorizingFormRequest;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\FormRequestWithAuthorize;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\FormRequestWithAuthorizeButNotReally;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\FormRequestWithoutAuthorize;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\SingleActionControllerWithAuthorizeCall;

class DetectionTest extends TestCase
{
    public function test_it_passes_with_controller_middleware(): void
    {
        Route::get('/', ControllerWithAuthorizationMiddleware::class)
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_with_route_middleware(): void
    {
        Route::get('/', [ControllerWithoutAuthorization::class, 'index'])
            ->middleware(['auth', 'can:do-stuff']);

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_without_authentication(): void
    {
        Route::get('/', [ControllerWithoutAuthorization::class, 'index']);

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_with_authorize_call(): void
    {
        Route::get('/', [ControllerWithAuthorizeCall::class, 'index'])
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_with_gate_call(): void
    {
        Route::get('/', [ControllerWithGateCall::class, 'index'])
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_single_action_controllers_with_authorize_call(): void
    {
        Route::get('/', SingleActionControllerWithAuthorizeCall::class)->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_with_form_request_that_authorizes(): void
    {
        Route::get('/', [ControllerWithAuthorizingFormRequest::class, 'index'])
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_detects_no_authorization(): void
    {
        Route::get('/', [ControllerWithoutAuthorization::class, 'index'])
            ->middleware('auth');

        $this->assertCount(1, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_detects_form_requests_without_authorization(): void
    {
        Route::get('/', [ControllerWithoutAuthorizingFormRequest::class, 'index'])
            ->middleware('auth');

        $this->assertCount(1, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_detects_closures_without_authorization(): void
    {
        Route::get('/', fn (): string => '')
            ->middleware('auth');

        $this->assertCount(1, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_closures_with_middleware(): void
    {
        Route::get('/', fn (): string => '')
            ->middleware(['auth', 'can:do-stuff']);

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_closures_with_gate(): void
    {
        Route::get('/', function (): string {
            Gate::authorize('do-stuff');

            return '';
        })->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_passes_closures_with_form_request_that_authorizes(): void
    {
        Route::get('/', function (FormRequestWithAuthorize $request): string {
            return '';
        })->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_detects_closures_with_form_request_that_authorizes_but_not_really(): void
    {
        Route::get('/', function (FormRequestWithAuthorizeButNotReally $request): string {
            return '';
        })->middleware('auth');

        $this->assertCount(1, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_detects_closure_with_form_requests_without_authorization(): void
    {
        Route::get('/', function (FormRequestWithoutAuthorize $request): string {
            return '';
        })->middleware('auth');

        $this->assertCount(1, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_ignores_routes_via_the_name(): void
    {
        config()->set('unauthorized-detection.ignore', ['ignore']);

        Route::get('/', fn (): string => '')
            ->middleware('auth')
            ->name('ignore');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_ignores_routes_via_the_uri(): void
    {
        config()->set('unauthorized-detection.ignore', ['/']);

        Route::get('/', fn (): string => '')
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_ignores_redirect_routes(): void
    {
        config()->set('unauthorized-detection.ignore', ['\Illuminate\Routing\RedirectController']);

        Route::redirect('/', 'temp')
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }

    public function test_it_excludes_broken_routes(): void
    {
        Route::get('/', [ControllerWithoutAuthorization::class, 'brokenRoute'])
            ->middleware('auth');

        $this->assertCount(0, app(Detector::class)->unauthorizedEndpoints());
    }
}
