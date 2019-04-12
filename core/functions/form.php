<?php

/**
 * Gauname saugu patikrinta user input.
 * 
 * @param type $form
 * @return type
 */
function get_safe_input($form) {
    $filtro_parametrai = [
        'action' => FILTER_SANITIZE_SPECIAL_CHARS
    ];
    foreach ($form['fields'] as $field_id => $value) {
        $filtro_parametrai[$field_id] = FILTER_SANITIZE_SPECIAL_CHARS;
    }
    return filter_input_array(INPUT_POST, $filtro_parametrai);
}

/**
 * Patikriname ar formoje esancios validacijos funkcijos yra teisingos ir iskvieciame ju funkcijas(not empty, not a number).
 * 
 * @param type $safe_input
 * @param type $form
 * @return boolean
 * @throws Exception
 */
function validate_form($safe_input, &$form) {
    $success = true;
    $form['validate'] = $form['validate'] ?? [];

    foreach ($form['pre_validate'] as $pre_validator) {
        if (is_callable($pre_validator)) {
            if (!$pre_validator($safe_input, $form)) {
                $success = false;
                break;
            }
        } else {
            throw new Exception(strtr('Not callable @validator function', [
                '@validator' => $validator
            ]));
        }
    }
    if ($success) {
        foreach ($form['fields'] as $field_id => &$field) {
            foreach ($field['validate'] as $validator) {
                if (is_callable($validator)) {
                    $field['id'] = $field_id;

                    if (!$validator($safe_input[$field_id], $field, $safe_input)) {
                        $success = false;
                        break;
                    }
                } else {
                    throw new Exception(strtr('Not callable @validator function', [
                        '@validator' => $validator
                    ]));
                }
            }
        }
    }
    if ($success) {
        $form['validate'] = $form['validate'] ?? [];

        foreach ($form['validate'] as $validator) {
            if (is_callable($validator)) {
                if (!$validator($safe_input, $form)) {
                    $success = false;
                    break;
                }
            } else {
                throw new Exception(strtr('Not callable @validator function', [
                    '@validator' => $validator
                ]));
            }
        }
    }

    if ($success) {
        foreach ($form['callbacks']['success'] as $callback) {
            if (is_callable($callback)) {
                $callback($safe_input, $form);
            } else {
                throw new Exception(strtr('Not callable @function function', [
                    '@function' => $callback
                ]));
            }
        }
    } else {
        foreach ($form['callbacks']['fail'] as $callback) {
            if (is_callable($callback)) {
                $callback($safe_input, $form);
            } else {
                throw new Exception(strtr('Not callable @function function', [
                    '@function' => $callback
                ]));
            }
        }
    }

    return $success;
}

/**
 * Checks if field is empty
 * 
 * @param string $field_input
 * @param array $field $form Field
 * @return boolean
 */
function validate_not_empty($field_input, &$field, $safe_input) {
    if (strlen($field_input) == 0) {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'kad palikai @field tuscia!', ['@field' => $field['label']
        ]);
    } else {
        return true;
    }
}

/**
 * Checks if field is a number
 * 
 * @param string $field_input
 * @param array $field $form Field
 * @return boolean
 */
function validate_is_number($field_input, &$field, $safe_input) {
    if (!is_numeric($field_input)) {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field nera skaicius!', ['@field' => $field['label']
        ]);
    } else {
        return true;
    }
}

function validate_file($field_input, &$field, &$safe_input) {
    $file = $_FILES[$field['id']] ?? false;
    if ($file) {
        if ($file['error'] == 0) {
            $safe_input[$field['id']] = $file;
            return true;
        }
    }

    $field['error_msg'] = 'Nenurodei fotkes';
}

function validate_email($field_input, &$field, &$safe_input) {
    if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $safe_input['email'])) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field blogai uzrasytas!', ['@field' => $field['label']
        ]);
    }
}

function validate_age($field_input, &$field, &$safe_input) {
    if ($safe_input['age'] > 0) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field negali but mazesnis uz 0!', ['@field' => $field['label']
        ]);
    }
}

function validate_contains_space($field_input, &$field, &$safe_input) {
    if (preg_match('/\s/', $safe_input['full_name'])) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field privalo tureti tarpa!', ['@field' => $field['label']
        ]);
    }
}

function validate_more_4_chars($field_input, &$field, &$safe_input) {
    if (strlen($safe_input['full_name']) > 4) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field privalo buti ilgesnis nei 4 simboliai', ['@field' => $field['label']
        ]);
    }
}

function validate_field_select($field_input, &$field, &$safe_input) {
    if (array_key_exists($field_input, $field['options'])) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field yra neteisingas', ['@field' => $field['label']
        ]);
    }
}

function validate_user_exists($field_input, &$field, &$safe_input) {
    $user = new Core\User\User();
    $user->setEmail($field_input);
    $db = new Core\FileDB(DB_FILE);
    $repo = new Core\User\Repository($db, TABLE_USERS);

    if (!$repo->exists($user)) {
        return true;
    } else {
        $field['error_msg'] = 'Tokiu emailu useris jau yra!';
    }
}

function validate_no_numbers($field_input, &$field, &$safe_input) {
    if (1 !== preg_match('~[0-9]~', $field_input)) {
        return true;
    }
    $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
            . 'nes @field negali būti skaičiu', ['@field' => $field['label']
    ]);
}

function validate_string_lenght_10_chars($field_input, &$field, &$safe_input) {
    if (strlen($field_input) > 10) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field privalo buti ilgesnis nei 10 simboliu', ['@field' => $field['label']
        ]);
    }
}

function validate_string_lenght_60_chars($field_input, &$field, &$safe_input) {
    if (strlen($field_input) < 60) {
        return true;
    } else {
        $field['error_msg'] = strtr('Jobans/a tu buhurs/gazele, '
                . 'nes @field privalo buti trumpesnis nei 60 simboliu', ['@field' => $field['label']
        ]);
    }
}
