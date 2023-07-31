<?php


namespace Salesforce;


class UrlRepository
{
    protected $instanceUrl;

    public function __construct(string $instanceUrl)
    {
        $this->instanceUrl = $instanceUrl;
    }

    /*  +++++++++++++++++++++++++++++Select sobject++++++++++++++++++++++++++++++++++++  */
    public function resources(): string
    {
        return "{$this->instanceUrl}/services/data/v50.0/sobjects";
    }


    /*  +++++++++++++++++++++++++++++create sobject++++++++++++++++++++++++++++++++++++  */
    public function objectResource(string $objectName): string
    {
        return "{$this->resources()}/{$objectName}";
    }



    /*
    public function objectResource(string $objectName): string
    {
        return "{$this->resources()}/{$objectName}";
    }

    public function objectRecord(string $objectName, string $id): string
    {
        return "{$this->objectResource($objectName)}/{$id}";
    }

    public function describe(string $sobjectName): string
    {
        return "{$this->resources()}/{$sobjectName}/describe";
    }

    public function selectQuery(string $objectName, array $fields): string
    {
        $fields = join(',+', $fields);

        $select = "SELECT+{$fields}+FROM+{$objectName}";

        return "{$this->instanceUrl}/services/data/v51.0/query?q={$select}";
    }
    */
}
