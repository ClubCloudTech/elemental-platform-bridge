<?php
/**
 * Menu Tab Object Class
 * Shows Menu Objects that can be rendered by tabs in Elemental and MyVideoRoom.
 *
 * @package entity/class-menutabdisplay.php
 */

namespace ElementalPlugin\Entity;

use ElementalPlugin\Module\Sandbox\Entity\SandboxEntity;

/**
 * Menu Tab Object Class
 * Shows Menu Objects that can be rendered by tabs in Elemental and MyVideoRoom.
 */
class MenuTabDisplay {

	/**
	 * Tab Display Name
	 *
	 * @var string $tab_display_name
	 */
	private string $tab_display_name;

	/**
	 * Tab slug
	 *
	 * @var string $tab_slug
	 */
	private string $tab_slug;

	/**
	 * CallBack Content
	 *
	 * @var callable $function_callback
	 */
	private $function_callback;

	/**
	 * CallBack Content
	 *
	 * @var string $element_id - the ID to use for the element
	 */
	private ?string $element_id = null;

	/**
	 * Sort Order
	 *
	 * @var ?int $sort_order - the sort order.
	 */
	private ?int $sort_order = null;

	/**
	 * Sort Order
	 *
	 * @var ?SandboxEntity $sandbox_object - the sandbox object.
	 */
	private ?SandboxEntity $sandbox_object;

	/**
	 * MenuTabDisplay constructor.
	 *
	 * @param string         $tab_display_name  Description of Tab.
	 * @param string         $tab_slug          Identifier of Tab for navigation.
	 * @param callable       $function_callback Function to display content.
	 * @param ?string        $element_id - the ID to use for the element.
	 * @param ?int           $sort_order - the sort order.
	 * @param ?SandboxEntity $sandbox_object - the sandbox object.
	 */
	public function __construct(
		string $tab_display_name,
		string $tab_slug,
		callable $function_callback,
		?string $element_id = null,
		?int $sort_order = null,
		?SandboxEntity $sandbox_object
	) {
		$this->tab_display_name  = $tab_display_name;
		$this->tab_slug          = $tab_slug;
		$this->function_callback = $function_callback;
		$this->element_id        = $element_id;
		$this->sort_order        = $sort_order;
		$this->sandbox_object    = $sandbox_object;
	}

	/**
	 * Gets Tab Display Name.
	 *
	 * @return string
	 */
	public function get_tab_display_name(): string {
		return $this->tab_display_name;
	}

	/**
	 * Gets Tab Slug.
	 *
	 * @return string
	 */
	public function get_tab_slug(): string {
		return $this->tab_slug;
	}

	/**
	 * Gets Function Callback.
	 *
	 * @return ?string
	 */
	public function get_function_callback(): ?string {
		return ( $this->function_callback )();
	}

	/**
	 * Gets Element ID.
	 *
	 * @return string
	 */
	public function get_element_id(): ?string {
		return $this->element_id;
	}

	/**
	 * Gets Sort Order.
	 *
	 * @return ?int
	 */
	public function get_sort_order(): ?int {
		return $this->sort_order;
	}
	/**
	 * Gets Sandbox Object.
	 *
	 * @return ?SandboxEntity
	 */
	public function get_sandbox_object(): ?SandboxEntity {
		return $this->sandbox_object;
	}
}
