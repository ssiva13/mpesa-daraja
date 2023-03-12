<?php
/**
 * Date 12/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http;

class AbstractDarajaQuery implements DarajaQueryInterface
{
    protected string $endpoint = '';
    protected CoreClient $coreClient;
    
    /**
     * Auth constructor.
     * @param CoreClient $coreClient
     */
    public function __construct(CoreClient $coreClient)
    {
        $this->coreClient = $coreClient;
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