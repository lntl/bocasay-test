<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\FileLocator;
use App\Repository\UserRepository;
use App\Entity\User;
use \Firebase\JWT\JWT;

class ApiAuthController extends AbstractController
{
    #[Route('/api/auth', name: 'api_auth')]
    public function index(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAuz6ZnDOOes1nl3pALV/N
        /XybHaiOBScS5JUHrzfqFz0ZgzuNKEMeOkxgn4YxHo/rLZJPVFwj5P6GmmBGLtwL
        VLuxwj5jPurX1d/hHN6+hnm7oqatsBpJuFolXglKQZQ+jV5UCmcnEKzlCx1fmHfK
        ++n+DqQUMWtREdc9+nOrpkgxfo9KY4tmKk2POwKI75f1ROlvef/cw4xn9Z70Huul
        3Pu3HxxJ2AQ5u9Tw74EgtDOeJNTJFU7mayvKKhSxQN1GNWD3RkNMEtqTOjfqqmNH
        lf7GNQ9JLemJw70l2aXCYFq0L4NIechODD/8Fces60PvcxHdiCcRQjG3KGVeMKWK
        96K6BZ1jjglFHzu+8xBPTzxPAmqBl8KsjeB5ZdyWD1jOqCmnjd7OfVmRLu3sHUeN
        jHpn3F7uCcWB3pxQMzxwllxpFTnztR6G4fhiWNxvUFKmiP2TwHOTeNcCdN09YjX8
        czLqRmvVCh+2MJmF18W/7/+EJvO4Ns/q5OlnTFvD251OgkilmmdaHL6tHOoAo61V
        TK6Pb78tPgQQ7NAzGVo3v6rFIvPgm5PXGqHwCSrqgSVcj2DQbGhSl4vKQQD2o5ny
        dqxiwQx0j9pltBONeP2v2CkN1fevoWp7ik8CJ9OHAYPvlLzqm3yrmOSEEkzEi1me
        7s5jHmMCBEZPWmYsongajGMCAwEAAQ==
        -----END PUBLIC KEY-----
        EOD;

        $privateKey = <<<EOD
        -----BEGIN RSA PRIVATE KEY-----
        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
        -----END RSA PRIVATE KEY-----
        EOD;
        
        if(!empty($_POST["email"])){
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $_POST['email']
            ]);
            
            if($user){
                if($encoder->isPasswordValid($user, $_POST['password'])){
                    $payload = [
                        'id' => $user->getId(),
                        'firstname' => $user->getFirstName(),
                        'lastname' => $user->getLastName(),
                        'roles'=> $user->getRoles(),
                    ];
                    
                    $jwt = JWT::encode($payload, $privateKey, 'RS256');
                    return new JsonResponse([
                        'jwt' => $jwt,
                    ]);
                }
            } else {
                return new JsonResponse([
                    'jwt' => "false",
                ]);
            }
        } else {
            $jwt = JWT::decode($_GET['jwt'], $publicKey, array('RS256'));
            return new JsonResponse([
                'jwt' => $jwt,
            ]);
        }
        
    }
}
