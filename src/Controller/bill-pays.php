<?php

    use Psr\Http\Message\ServerRequestInterface;

    $app->get("/bill-pays", function() use($app)
    {
        $view = $app->service("view.renderer");
        /** @var \MMoney\Repository\RepositoryInterface $repository */
        $repository = $app->service("bill-pays.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $pays = $repository->findByField("user_id", $auth->user()->getId());
        return $view->render("bill-pays/list.html.twig", ["pays"=>$pays]);
    }, "bill-pays.list")
    ->get("/bill-pays/new", function() use($app)
    {
        $view = $app->service("view.renderer");
        $auth = $app->service("auth");
        $categoryRepository = $app->service("category-costs.repository");
        $categories = $categoryRepository->findByField("user_id", $auth->user()->getId());
        return $view->render("bill-pays/create.html.twig", ["categories"=>$categories]);
    }, "bill-pays.new")
    ->post("/bill-pays/store", function(ServerRequestInterface $request)use($app)
    {
        $data = $request->getParsedBody();
        $repository = $app->service("bill-pays.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $user = $auth->user();
        $data["user_id"] = $user->getId();

        $data["date_launch"] = dateParse($data["date_launch"]);
        $data["value"] = numberParse($data["value"]);

        $categoryRepository = $app->service("category-costs.repository");

        $data["category_cost_id"] =  $categoryRepository->findOneBy([
            "id" => $data["category_cost_id"],
            "user_id" => $user->getId()
        ])->id;

        $repository->create($data);
        return $app->route("bill-pays.list");
    }, "bill-pays.store")
    ->get("/bill-pays/{id}/edit", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $id = $request->getAttribute("id");
        $repository = $app->service("bill-pays.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $userId = $auth->user()->getId();
        $pay = $repository->findOneBy([
            "id" => $id,
            "user_id" => $userId
        ]);

        $categoryRepository = $app->service("category-costs.repository");
        $categories = $categoryRepository->findByField("user_id", $userId);

        return $view->render("bill-pays/edit.html.twig", ["pay"=>$pay, "categories"=>$categories]);
    }, "bill-pays.edit");

    $app->post("/bill-pays/{id}/update", function(ServerRequestInterface $request) use($app)
    {
        $id = $request->getAttribute("id");
        $data = $request->getParsedBody();
        $repository = $app->service("bill-pays.repository");

        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $data["user_id"] = $auth->user()->getId();
        $data["date_launch"] = dateParse($data["date_launch"]);
        $data["value"] = numberParse($data["value"]);

        $categoryRepository = $app->service("category-costs.repository");

        $data["category_cost_id"] =  $categoryRepository->findOneBy([
            "id" => $data["category_cost_id"],
            "user_id" => $auth->user()->getId()
        ])->id;

        $repository->update([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ], $data);
        return $app->route("bill-pays.list");
    }, "bill-pays.update");

    $app->get("/bill-pays/{id}/show", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $id = $request->getAttribute("id");
        $repository = $app->service("bill-pays.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $pay = $repository->findOneBy([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ]);
        return $view->render("bill-pays/show.html.twig", ["pay"=>$pay]);
    }, "bill-pays.show");

    $app->get("/bill-pays/{id}/delete", function(ServerRequestInterface $request) use($app)
    {
        $id = $request->getAttribute("id");
        $repository = $app->service("bill-pays.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $repository->delete([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ]);
        return $app->route("bill-pays.list");
    }, "bill-pays.delete");