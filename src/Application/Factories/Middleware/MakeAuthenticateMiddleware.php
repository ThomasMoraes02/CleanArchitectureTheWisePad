<?php 
namespace CleanArchitecture\Application\Factories\Middleware;

use CleanArchitecture\Application\Helpers\HttpStatusHelper;
use DI\Container;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use CleanArchitecture\Application\Middleware\Authentication;
use CleanArchitecture\Application\Middleware\Middleware;
use Exception;
use Throwable;

class MakeAuthenticateMiddleware
{
    use HttpStatusHelper;

    private Middleware $middleware;

    public function __construct(Container $container)
    {
        $this->middleware = $container->get("Middleware");
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        try {
            $token = $request->getHeader("Authorization");    
            $responseOperation = $this->middleware->authenticate(current($token));
    
            if(!$responseOperation) {
                throw new Exception("Token Inválido");
            }
        
            $response = $handler->handle($request);
            return $response;
        } catch(Throwable $e) {
            $data = $this->forbidden($e->getMessage());
            return $this->response($data);
        }
    }

    /**
     * Return Response
     *
     * @param array $data
     * @return Response
     */
    private function response(array $data): Response
    {
        $response = new Response();
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader("Cache-Control", "no-cache")
            ->withHeader("Cache-Control", "max-age=0")
            ->withHeader("Cache-Control", "must-revalidate")
            ->withHeader("Cache-Control", "proxy-revalidate")
            ->withStatus($data['statusCode']);
    }
}