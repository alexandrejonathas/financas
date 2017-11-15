<?php

    use Psr\Http\Message\ServerRequestInterface;

    $app->get("/bill-receives", function() use($app)
    {
        $view = $app->service("view.renderer");
        /** @var \MMoney\Repository\RepositoryInterface $repository */
        $repository = $app->service("bill-receives.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $receives = $repository->findByField("user_id", $auth->user()->getId());
        return $view->render("bill-receives/list.html.twig", ["receives"=>$receives]);
    }, "bill-receives.list")
    ->get("/bill-receives/new", function() use($app)
    {
        $view = $app->service("view.renderer");
        return $view->render("bill-receives/create.html.twig");
    }, "bill-receives.new")
    ->post("/bill-receives/store", function(ServerRequestInterface $request)use($app)
    {
        $data = $request->getParsedBody();
        $repository = $app->service("bill-receives.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $data["user_id"] = $auth->user()->getId();
        echo $data["date_launch"];

        $data["date_launch"] = dateParse($data["date_launch"]);
        $data["value"] = numberParse($data["value"]);
        $repository->create($data);
        return $app->route("bill-receives.list");
    }, "bill-receives.store")
    ->get("/bill-receives/{id}/edit", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $id = $request->getAttribute("id");
        $repository = $app->service("bill-receives.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $receive = $repository->findOneBy([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ]);
        return $view->render("bill-receives/edit.html.twig", ["receive"=>$receive]);
    }, "bill-receives.edit");

    $app->post("/bill-receives/{id}/update", function(ServerRequestInterface $request) use($app)
    {
        $id = $request->getAttribute("id");
        $data = $request->getParsedBody();
        $repository = $app->service("bill-receives.repository");

        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $data["user_id"] = $auth->user()->getId();
        $data["date_launch"] = dateParse($data["date_launch"]);
        $data["value"] = numberParse($data["value"]);
        $repository->update([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ], $data);
        return $app->route("bill-receives.list");
    }, "bill-receives.update");

    $app->get("/bill-receives/{id}/show", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $id = $request->getAttribute("id");
        $repository = $app->service("bill-receives.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $receive = $repository->findOneBy([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ]);
        return $view->render("bill-receives/show.html.twig", ["receive"=>$receive]);
    }, "bill-receives.show");

    $app->get("/bill-receives/{id}/delete", function(ServerRequestInterface $request) use($app)
    {
        $id = $request->getAttribute("id");
        $repository = $app->service("bill-receives.repository");
        /** @var \MMoney\Auth\Auth $auth */
        $auth = $app->service("auth");
        $repository->delete([
            "id" => $id,
            "user_id" => $auth->user()->getId()
        ]);
        return $app->route("bill-receives.list");
    }, "bill-receives.delete");