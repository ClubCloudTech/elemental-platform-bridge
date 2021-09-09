<?php
/**
 * A User Video Preference
 *
 * @package MyVideoRoomExtrasPlugin\Entity
 */

namespace MyVideoRoomExtrasPlugin\Entity;

/**
 * Class SecurityVideoPreference
 */
class SecurityVideoPreference {

	private int $user_id;
	private string $room_name;
	private ?string $allowed_roles;
	private ?string $blocked_roles;
	private bool $room_disabled;
	private bool $site_override_enabled;
	private bool $anonymous_enabled;
	private bool $allow_role_control_enabled;
	private bool $block_role_control_enabled;
	private ?string $restrict_group_to_members_setting;
	private ?string $bp_friends_setting;




	/**
	 * SecurityVideoPreference constructor.
	 *
	 * @param int         $user_id
	 * @param string      $room_name
	 * @param string|null $allowed_roles
	 * @param string|null $blocked_roles
	 * @param bool        $room_disabled
	 */
	public function __construct(
		int $user_id,
		string $room_name,
		string $allowed_roles = null,
		string $blocked_roles = null,
		bool $room_disabled = false,
		bool $anonymous_enabled = false,
		bool $allow_role_control_enabled = false,
		bool $block_role_control_enabled = false,
		bool $site_override_enabled = false,
		string $restrict_group_to_members_setting = null,
		string $bp_friends_setting = null

	) {
		$this->user_id                           = $user_id;
		$this->room_name                         = $room_name;
		$this->allowed_roles                     = $allowed_roles;
		$this->blocked_roles                     = $blocked_roles;
		$this->room_disabled                     = $room_disabled;
		$this->anonymous_enabled                 = $anonymous_enabled;
		$this->allow_role_control_enabled        = $allow_role_control_enabled;
		$this->block_role_control_enabled        = $block_role_control_enabled;
		$this->site_override_enabled             = $site_override_enabled;
		$this->restrict_group_to_members_setting = $restrict_group_to_members_setting;
		$this->bp_friends_setting                = $bp_friends_setting;
	}

	/**
	 * @return int
	 */
	public function get_user_id(): int {
		return $this->user_id;
	}


	/**
	 * @return string
	 */
	public function get_room_name(): string {
		return $this->room_name;
	}

	/**
	 * @return string
	 */
	public function get_allowed_roles(): ?string {
		return $this->allowed_roles;
	}

	/**
	 * @param string|null $allowed_roles
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_allowed_roles( string $allowed_roles = null ): SecurityVideoPreference {
		$this->allowed_roles = $allowed_roles;
		return $this;
	}

	/**
	 * @return string
	 */
	public function get_blocked_roles(): ?string {
		return $this->blocked_roles;
	}

	/**
	 * @param string|null $blocked_roles
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_blocked_roles( string $blocked_roles = null ): SecurityVideoPreference {
		$this->blocked_roles = $blocked_roles;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_room_disabled(): bool {
		return $this->room_disabled;
	}

	/**
	 * @param bool $room_disabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_room_disabled( bool $room_disabled ): SecurityVideoPreference {
		$this->room_disabled = $room_disabled;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_anonymous_enabled(): bool {
		return $this->anonymous_enabled;
	}

	/**
	 * @param bool $room_disabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_anonymous_enabled( bool $anonymous_enabled ): SecurityVideoPreference {
		$this->anonymous_enabled = $anonymous_enabled;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function is_allow_role_control_enabled(): bool {
		return $this->allow_role_control_enabled;
	}

	/**
	 * @param bool $room_disabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_allow_role_control_enabled( bool $allow_role_control_enabled ): SecurityVideoPreference {
		$this->allow_role_control_enabled = $allow_role_control_enabled;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function is_block_role_control_enabled(): bool {
		return $this->block_role_control_enabled;
	}

	/**
	 * @param bool $room_disabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_block_role_control_enabled( bool $block_role_control_enabled ): SecurityVideoPreference {
		$this->block_role_control_enabled = $block_role_control_enabled;
		return $this;
	}

	/**Site Override
	 *
	 * @return bool
	 */
	public function check_site_override_setting(): bool {
		return $this->site_override_enabled;
	}

	/**Site Override
	 *
	 * @param bool $site_override_enabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_site_override_setting( bool $site_override_enabled ): SecurityVideoPreference {
		$this->site_override_enabled = $site_override_enabled;
		return $this;
	}
	/**
	 *
	 */
	public function check_restrict_group_to_members_setting(): ?string {
		return $this->restrict_group_to_members_enabled;
	}

	/**
	 * @param bool $room_disabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_restrict_group_to_members_setting( $restrict_group_to_members_enabled ): SecurityVideoPreference {
		$this->restrict_group_to_members_enabled = $restrict_group_to_members_enabled;
		return $this;
	}


	/**
	 *
	 */
	public function check_bp_friends_setting(): ?string {
		return $this->bp_friends_setting;
	}

	/**
	 * @param bool $room_disabled
	 *
	 * @return SecurityVideoPreference
	 */
	public function set_bp_friends_setting( $bp_friends_setting ): SecurityVideoPreference {
		$this->bp_friends_setting = $bp_friends_setting;
		return $this;
	}






}
