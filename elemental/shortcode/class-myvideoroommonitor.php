<?php
/**
 * Represents a myvideoroom_monitor shortcode
 *
 * @package MyVideoRoomExtrasPlugin\ValueObjects
 */

namespace MyVideoRoomExtrasPlugin\Shortcode;

/**
 * Class MyVideoRoomMonitor
 */
class MyVideoRoomMonitor extends Shortcode {

	public const MYVIDEOROOM_MONITOR_SHORTCODE = 'myvideoroom_monitor';

	/**
	 * The type of the monitor
	 * ['reception', 'seated', 'all']
	 *
	 * @var string
	 */
	private string $type;

	/**
	 * The name of the room to monitor
	 *
	 * @var string name.
	 */
	private string $name;

	/**
	 * The text to show while loading
	 *
	 * @var string
	 */
	private string $text_loading;

	/**
	 * The text to show if the room is empty
	 *
	 * @var string
	 */
	private string $text_empty;

	/**
	 * The text to show if a single person is in the room
	 *
	 * @var string
	 */
	private string $text_single;

	/**
	 * The plain text to show if a single person is in the room
	 *
	 * @var string
	 */
	private string $text_single_plain;

	/**
	 * The text to show if multiple people are in the room
	 *
	 * @var string
	 */
	private string $text_plural;

	/**
	 * The plain text to show if multiple people are in the room
	 *
	 * @var string
	 */
	private string $text_plural_plain;

	/**
	 * Create an instance - allows for easier chaining
	 *
	 * @param string $type The type of the monitor.
	 * @param string $name The text to show while loading.
	 * @param string $text_loading The text to show while loading.
	 * @param string $text_empty The text to show if the room is empty.
	 * @param string $text_single The text to show if a single person is in the room.
	 * @param string $text_single_plain The plain text to show if a single person is in the room.
	 * @param string $text_plural The text to show if multiple people are in the room.
	 * @param string $text_plural_plain The plain text to show if multiple people are in the room.
	 *
	 * @return static
	 */
	public static function create_instance(
		string $type,
		string $name,
		string $text_loading,
		string $text_empty,
		string $text_single,
		string $text_single_plain,
		string $text_plural,
		string $text_plural_plain ): self {
		return new self(
			$type,
			$name,
			$text_loading,
			$text_empty,
			$text_single,
			$text_single_plain,
			$text_plural,
			$text_plural_plain
		);
	}


	/**
	 * MyVideoRoomMonitor constructor.
	 *
	 * @param string $type The type of the monitor.
	 * @param string $name The text to show while loading.
	 * @param string $text_loading The text to show while loading.
	 * @param string $text_empty The text to show if the room is empty.
	 * @param string $text_single The text to show if a single person is in the room.
	 * @param string $text_single_plain The plain text to show if a single person is in the room.
	 * @param string $text_plural The text to show if multiple people are in the room.
	 * @param string $text_plural_plain The plain text to show if multiple people are in the room.
	 */
	public function __construct(
		string $type,
		string $name,
		string $text_loading,
		string $text_empty,
		string $text_single,
		string $text_single_plain,
		string $text_plural,
		string $text_plural_plain
	) {
		$this->type              = $type;
		$this->name              = $name;
		$this->text_loading      = $text_loading;
		$this->text_empty        = $text_empty;
		$this->text_single       = $text_single;
		$this->text_single_plain = $text_single_plain;
		$this->text_plural       = $text_plural;
		$this->text_plural_plain = $text_plural_plain;
	}


	/**
	 * Render out the shortcode
	 *
	 * @return string
	 */
	public function output_shortcode(): string {
		$shortcode_array = array(
			'type'              => $this->type,
			'name'              => $this->name,
			'text-loading'      => $this->text_loading,
			'text-empty'        => $this->text_empty,
			'text-single'       => $this->text_single,
			'text-single-plain' => $this->text_single_plain,
			'text-plural'       => $this->text_plural,
			'text-plural-plain' => $this->text_plural_plain,
		);

		return $this->render_shortcode( self::MYVIDEOROOM_MONITOR_SHORTCODE, $shortcode_array );
	}

}
