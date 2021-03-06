<?php

namespace Shepard\Tests\Entity;

class ExampleEntityProvider
{
    /**
     * @return array|ExampleEntity[]
     */
    public static function getFixedUserList()
    {
        $userList = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new ExampleEntity($i, "Mr." . $i, "00-00-000-000" . $i, 'email.Mr' . $i . "@unknown.com");
            $userList[$i] = $user;
        }
        /** @var ExampleEntity[] $userList */
        $userList[0]->setNodes([$userList[1], $userList[2], $userList[3]]);
        $userList[1]->setNodes([$userList[4], $userList[5]]);
        $userList[2]->setNodes([$userList[6], $userList[7], $userList[8]]);
        $userList[7]->setNodes([$userList[9]]);

        return $userList;
    }

    /**
     * @param int $count
     * @param int $minimumNodeCount
     * @param int $maximumNodeCount
     * @return ExampleEntity[]
     */
    public static function generate($count, $minimumNodeCount = 3, $maximumNodeCount = 3)
    {
        /** @var ExampleEntity[] $entityList */
        $entityList = [];
        $nodes = [];
        array_push($entityList, new ExampleEntity(0, self::getName(0), "06-30-123-4567", "foo@bar.com"));

        $numberOfChildNodes = rand($minimumNodeCount, $maximumNodeCount);
        $currentEntityId = 0;

        for ($i = 1; $i < $count; $i++) {
            $user = new ExampleEntity($i, self::getName($i), "06-30-123-4567", "foo@bar.com");
            array_push($entityList, $user);
            array_push($nodes, $user);

            if (sizeof($entityList) % $numberOfChildNodes == 1) {
                $entityList[$currentEntityId]->setNodes($nodes);
                unset($nodes);
                $nodes = [];
                $numberOfChildNodes = rand($minimumNodeCount, $maximumNodeCount);
                $currentEntityId++;
            }
        }

        return $entityList;
    }

    /**
     * @param int $i
     * @return string
     */
    private static function getName($i)
    {
        $nameList = ["Carlotta Paynter", "Stacee Whelpley", "Delisa Aller", "Maren Coogan", "Iris Sesco",
            "Michele Holzinger", "Jazmine Sater", "Isidro Wieczorek", "Rogelio Marron", "Julio Tedeschi", "Leanne Postma",
            "Carol Woody", "Flora Commons", "Lavonne Hartwig", "Milagro Whidden", "Nathaniel Bechtel", "Fernanda Ballow",
            "Shira Bernhard", "Kathryn Valletta", "Collette Kriger", "Lael Petermann", "Brittani Gendreau", "Lamar Munsell",
            "Laurena Laramee", "Holley Kreiger", "Candra Hammond", "Humberto Eleby", "Kaye Sak", "Bradly Patino",
            "Lyman Tremper", "Arthur Boise", "Barbar Rowlett", "Jackeline Devaney", "Alline Gladding", "Sherrie Reiff",
            "Kevin Bulkley", "Miles Ware", "Cari Blakes", "Randolph Longley", "Anisa Ocegueda", "Dewayne Depasquale",
            "Santo Curtin", "Francesco Stuber", "Kaycee Garlock", "Kena Lee", "Tommye Mccorvey", "Fiona Deitz",
            "Jaquelyn Ingerson", "Gertie Shawn", "Angelika Tobia"];

        return $nameList[$i % count($nameList)];
    }
}
