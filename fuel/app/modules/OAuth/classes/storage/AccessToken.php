<?php

namespace Oauth\Storage;

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\AccessTokenInterface;

class AccessToken extends AbstractStorage implements AccessTokenInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token)
    {
        $access_token = \Oauth\Model\AccessTokens::query()->where('access_token',$token)->get_one();

        if (count($access_token) === 1) {
            $token = (new AccessTokenEntity($this->server))
                        ->setId($access_token->access_token)
                        ->setExpireTime($access_token->expire_time);

            return $token;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(AccessTokenEntity $token)
    {

        $accessToken = \Oauth\Model\AccessTokens::query()->where('access_token',$token->getId())->related(array('scopes'));
        $response = array();
        if (property_exists($accessToken,'scopes')){
            $scopes = $accessToken->scopes;
            if (count($scopes) > 0) {
                foreach ($scopes as $scope) {
                    $Scope = (new ScopeEntity($this->server))->hydrate([
                        'id'            =>  $scope->id,
                        'description'   =>  $scope->description,
                    ]);
                    $response[] = $Scope;
                }
            }
        }
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $sessionId)
    {
        $accessToken = \Oauth\Model\AccessTokens::forge(
            array(
                'access_token'  =>  $token,
                'session_id'    =>  $sessionId,
                'expire_time'   =>  $expireTime,
            )
        );
        $accessToken->save();
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    {
        $access_token = \Oauth\Model\AccessTokens::query()->where('access_token',$token->getId())->related(array('scopes'));
        $access_token->scopes[$scope->getId()] = \Oauth\Model\Scopes::find($scope->getId());
        $access_token->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(AccessTokenEntity $token)
    {
        $access_token = \Oauth\Model\AccessTokens::query()->where('access_token',$token->getId());
        $access_token->delete();
    }
}
