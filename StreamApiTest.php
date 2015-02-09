<?php

use App\Http\Controllers\Api\StreamApiController;
use App\Libs\Users\UserRepository;

class StreamApiTest extends TestCase {

	/**
	 * @var UserRepository
	 */
	private $UserRepository;

	public function init()
	{

	}

	public function setUp()
	{
		parent::setUp();
		$this->cleanMongo('users');
		$this->UserRepository = $this->getApp()->make(UserRepository::class);
	}

	public function testMakeOnline()
	{
		$ChannelUser = $this->Generate->user()
		                              ->offline()
		                              ->get();

		$data = [
			'name' => $ChannelUser->getChannelKey(),
			'pwd'  => $ChannelUser->Channel->streamKey
		];
		$this->action(StreamApiController::class, 'makeOnline', $data);

		$this->assertResponseOk();
		$ChannelUser = $this->UserRepository->findById($ChannelUser->getId());
		$this->assertEquals(STATUS_ACTIVE, $ChannelUser->Channel->online);
	}

	public function testMakeOffline()
	{
		$ChannelUser = $this->Generate->user()
		                              ->online()
		                              ->get();

		$data = [
			'name' => $ChannelUser->getChannelKey()
		];
		$this->action(StreamApiController::class, 'makeOffline', $data);

		$this->assertResponseOk();
		$ChannelUser = $this->UserRepository->findById($ChannelUser->getId());
		$this->assertEquals(STATUS_INACTIVE, $ChannelUser->Channel->online);
	}

	public function testUpdateViewers()
	{
		$ChannelUser = $this->Generate->user()
		                              ->online()
		                              ->get();

		$viewers = rand(0, 1000);
		$data = [
			$ChannelUser->getChannelKey() => $viewers
		];
		$this->action(StreamApiController::class, 'updateViewers', $data);

		$this->assertResponseOk();
		$ChannelUser = $this->UserRepository->findById($ChannelUser->getId());
		$this->assertEquals($viewers, $ChannelUser->Channel->viewers);
	}
}
