<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reasun_Abonement_Pay_From_URL
 * @subpackage Reasun_Abonement_Pay_From_URL/admin/partials
 */
?>

    <h2>Test<?php echo esc_html( get_admin_page_title() ); ?></h2>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form method="post" name="my_options" action="options.php">
 
        <?php wp_nonce_field('update-options');

        // Загрузить все значения элементов формы
        $options = get_option($this->plugin_name);

        // текущие состояние опций
        $host_1c = $options['host_1c'];
  /*       $login_1c = $options['login_1c'];
        $pwd_1c = $options['pwd_1c']; */

        // Выводит скрытые поля формы на странице настроек
        settings_fields( $this->plugin_name );
        do_settings_sections( $this->plugin_name );
        
        ?>

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

        <fieldset>
            <legend class="screen-reader-text"><span><?php esc_attr_e('Ссылка для веб-сервиса 1с', $this->plugin_name);?></span></legend>
            <label for="<?php echo $this->plugin_name;?>-host_1c">
                <span><?php esc_attr_e('Ссылка для веб-сервиса 1с', $this->plugin_name);?></span>
            </label>
            <input type="text"
                   class="regular-text" id="<?php echo $this->plugin_name;?>-host_1c"
                   name="<?php echo $this->plugin_name;?>[host_1c]"
                   value="<?php if(!empty($host_1c)) esc_attr_e($host_1c, $this->plugin_name);?>"
                   placeholder="<?php esc_attr_e('Ссылка для веб-сервиса 1с', $this->plugin_name);?>"
            />
        </fieldset>
    <?/*    <fieldset>
            <legend class="screen-reader-text"><span><?php esc_attr_e('Логин 1С', $this->plugin_name);?></span></legend>
            <label for="<?php echo $this->plugin_name;?>-login_1c">
                <span><?php esc_attr_e('Логин 1С', $this->plugin_name);?></span>
            </label>
            <input type="text"
                   class="regular-text" id="<?php echo $this->plugin_name;?>-login_1c"
                   name="<?php echo $this->plugin_name;?>[login_1c]"
                   value="<?php if(!empty($login_1c)) esc_attr_e($login_1c, $this->plugin_name);?>"
                   placeholder="<?php esc_attr_e('Логин 1С', $this->plugin_name);?>"
            />
        </fieldset>

        <fieldset>
            <legend class="screen-reader-text"><span><?php esc_attr_e('Пароль для веб-сервиса 1С', $this->plugin_name);?></span></legend>
            <label for="<?php echo $this->plugin_name;?>-pwd_1c">
                <span><?php esc_attr_e('Пароль для веб-сервиса 1С', $this->plugin_name);?></span>
            </label>
            <input type="text"
                   class="regular-text" id="<?php echo $this->plugin_name;?>-pwd_1c"
                   name="<?php echo $this->plugin_name;?>[pwd_1c]"
                   value="<?php if(!empty($pwd_1c)) esc_attr_e($pwd_1c, $this->plugin_name);?>"
                   placeholder="<?php esc_attr_e('Пароль для веб-сервиса 1С', $this->plugin_name);?>"
            />
        </fieldset>
*/?>
        <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>

  </form>