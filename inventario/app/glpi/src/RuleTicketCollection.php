<?php

class RuleTicketCollection extends RuleCommonITILObjectCollection
{
    // From RuleCollection
    public static string $rightname    = 'rule_ticket';
    public string $menu_option         = 'ticket';

    public function getTitle()
    {
        return __('Business rules for tickets');
    }

    public function prepareInputDataForProcess($input, $params)
    {
        // Pass x-priority header if exists
        if (isset($input['_head']['x-priority'])) {
            $input['_x-priority'] = $input['_head']['x-priority'];
        }

        // Pass From header if exists
        if (isset($input['_head']['from'])) {
            $input['_from'] = $input['_head']['from'];
        }

        // Pass Subject header if exists
        if (isset($input['_head']['subject'])) {
            $input['_subject'] = $input['_head']['subject'];
        }

        // Pass Reply-To header if exists
        if (isset($input['_head']['reply-to'])) {
            $input['_reply-to'] = $input['_head']['reply-to'];
        }

        // Pass In-Reply-To header if exists
        if (isset($input['_head']['in-reply-to'])) {
            $input['_in-reply-to'] = $input['_head']['in-reply-to'];
        }

        // Pass To header if exists. Match against every recipient individually
        if (!empty($input['_head']['tos'])) {
            $input['_to'] = $input['_head']['tos'];
        } elseif (isset($input['_head']['to'])) {
            $input['_to'] = $input['_head']['to'];
        }

        return parent::prepareInputDataForProcess($input, $params);
    }
}
