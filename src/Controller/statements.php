<?php

    use Psr\Http\Message\ServerRequestInterface;

    $app->get("/statements", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $repository = $app->service("statements.repository");
        $auth = $app->service("auth");

        $data = $request->getQueryParams();

        $dateStart = $data["date_start"] ?? (new DateTime())->modify("-1 month");
        $dateStart = $dateStart instanceof \DateTime ? $dateStart->format("Y-m-d")
            : \DateTime::createFromFormat("d/m/Y", $dateStart)->format("Y-m-d");


        $dateEnd = $data["date_end"] ?? new DateTime();
        $dateEnd = $dateEnd instanceof \DateTime ? $dateEnd->format("Y-m-d")
            : \DateTime::createFromFormat("d/m/Y", $dateEnd)->format("Y-m-d");

        $userId = $auth->user()->getId();

        $statements = $repository->all($dateStart, $dateEnd, $userId);

        return $view->render("statements/list.html.twig", ["statements"=>$statements]);
    }, "statements.list");