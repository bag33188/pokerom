<?php

namespace App\Rules;

use App;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\RequiredIf;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/** Use this rule to require a field on a PUT request, and leave it optional/not required on a PATCH request */
class RequiredIfPutRequest extends RequiredIf
{
    private readonly Request $httpRequest;

    private const ALLOWED_HTTP_METHODS = array('put' => 'PUT', 'patch' => 'PATCH');

    /** @var callable|bool */
    public $condition;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->httpRequest = $request;
        $this->condition = $this->httpRequestMethodIsAPutRequest();

        $this->validateIfRuleIsBeingUsedCorrectly();

        parent::__construct($this->condition);
    }

    /**
     * @throws BadRequestHttpException
     */
    private function validateIfRuleIsBeingUsedCorrectly(): void
    {
        $exceptionMessage = self::generateExceptionMessage($this->getHttpRequestMethod());

        if ($this->requestMethodIsNotPutOrPatch()) throw new BadRequestHttpException($exceptionMessage);
    }

    private function httpRequestMethodIsAPutRequest(): bool
    {
        return $this->getHttpRequestMethod() === self::ALLOWED_HTTP_METHODS['put'];
    }

    private function requestMethodIsNotPutOrPatch(): bool
    {
        $requestMethod = $this->getHttpRequestMethod();

        return
            $requestMethod !== self::ALLOWED_HTTP_METHODS['put']
            and
            $requestMethod !== self::ALLOWED_HTTP_METHODS['patch'];
    }

    private function getHttpRequestMethod(): string
    {
        return $this->httpRequest->method();
    }

    private static function generateExceptionMessage(string $currentRequestMethodName): string
    {
        return App::isLocal() ? sprintf(
            "Error: Validation Rule `%s` can only be used on a `%s` or `%s` request. Current request method is a `%s` request.",
            self::class,
            self::ALLOWED_HTTP_METHODS['put'],
            self::ALLOWED_HTTP_METHODS['patch'],
            $currentRequestMethodName
        ) : 'Error: Bad request';
    }
}
