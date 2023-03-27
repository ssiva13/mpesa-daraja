<?php
/**
 * Date 12/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http;

abstract class AbstractDarajaQuery implements DarajaQueryInterface
{
    protected string $endpoint = '';
    protected CoreClient $coreClient;
    protected array $validationRules = [];
    
    /**
     * Auth constructor.
     *
     * @param CoreClient $coreClient
     *
     * @throws \ReflectionException
     */
    public function __construct(CoreClient $coreClient)
    {
        $this->coreClient = $coreClient;
        $this->coreClient->setValidationRules($this->validationRules);
    }
    
    /**
     * @inheritDoc
     */
    public function bearer($app): string
    {
        return $this->coreClient->auth->authenticate($app);
    }
    
    /**
     * @inheritDoc
     */
    public function submitRequest(array $params = [], string $app = 'default')
    {
        // TODO: Implement submit() method.
    }
}
