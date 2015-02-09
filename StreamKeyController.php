<?php namespace App\Http\Controllers\Channels;

use App\Http\Bootstraps\ChannelBoot;
use App\Libs\Response\JsonApiResponse;

class StreamKeyController extends BaseChannelController {

	/**
	 * Get channel stream key.
	 *
	 * @Get("channels/{key}/stream-key")
	 * @param ChannelBoot $ChannelBoot
	 *
	 * @return JsonApiResponse
	 * @throws \App\Libs\Core\Bootstrap\BootstrapResult
	 */
	public function updateBasic( ChannelBoot $ChannelBoot )
	{
		$ChannelBoot->checkAccess();
		$Channel = $ChannelBoot->getChannel();

		return $this->response([
			'value' => $Channel->Channel->streamKey
		], 'data')->success();
	}

	/**
	 * Generate new channel stream key.
	 *
	 * @Put("channels/{key}/stream-key")
	 * @param ChannelBoot $ChannelBoot
	 *
	 * @return JsonApiResponse
	 * @throws \App\Libs\Core\Bootstrap\BootstrapResult
	 */
	public function updateStreamKey( ChannelBoot $ChannelBoot )
	{
		$ChannelBoot->checkAccess();
		$Channel = $ChannelBoot->getChannel();

		$this->UserManager->changeStreamKey($Channel);

		return $this->response([
			'value' => $Channel->Channel->streamKey
		], 'data')->success();
	}
}
