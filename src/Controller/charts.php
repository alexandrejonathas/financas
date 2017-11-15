<?php

    use Psr\Http\Message\ServerRequestInterface;

    $app->get("/charts", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $repository = $app->service("category-costs.repository");
        $auth = $app->service("auth");

        $data = $request->getQueryParams();

        $dateStart = $data["date_start"] ?? (new DateTime())->modify("-1 month");
        $dateStart = $dateStart instanceof \DateTime ? $dateStart->format("Y-m-d")
            : \DateTime::createFromFormat("d/m/Y", $dateStart)->format("Y-m-d");


        $dateEnd = $data["date_end"] ?? new DateTime();
        $dateEnd = $dateEnd instanceof \DateTime ? $dateEnd->format("Y-m-d")
            : \DateTime::createFromFormat("d/m/Y", $dateEnd)->format("Y-m-d");

        $userId = $auth->user()->getId();

        $categories = $repository->sumByPeriod($dateStart, $dateEnd, $userId);

        return $view->render("charts/show.html.twig", ["categories"=>$categories]);
    }, "charts.show");