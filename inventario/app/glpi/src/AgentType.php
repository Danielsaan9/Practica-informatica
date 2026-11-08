<?php

/**
 * @since 9.5
 **/
class AgentType extends CommonDBTM
{
    public static string $rightname = 'agent';

    public static function getTypeName($nb = 0)
    {
        return _n('Agent type', 'Agents types', $nb);
    }

    public function cleanDBonPurge()
    {
        $agent = new Agent();
        $agent->deleteByCriteria(['agenttypes_id' => $this->fields['id']]);
    }
}
