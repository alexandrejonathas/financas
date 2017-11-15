<?php

    use Psr\Http\Message\ServerRequestInterface;

    $app->get("/users", function() use($app)
    {
        $view = $app->service("view.renderer");
        $repository = $app->service("users.repository");
        $users = $repository->all();
        return $view->render("users/list.html.twig", ["users"=>$users]);
    }, "users.list")
    ->get("/users/new", function() use($app)
    {
        $view = $app->service("view.renderer");
        return $view->render("users/create.html.twig");
    }, "users.new")
    ->post("/users/store", function(ServerRequestInterface $request)use($app)
    {
        $data = $request->getParsedBody();
        $repository = $app->service("users.repository");
        $repository->create($data);
        return $app->route("users.list");
    }, "users.store")
    ->get("/users/{id}/edit", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $id = $request->getAttribute("id");
        $repository = $app->service("users.repository");
        $user = $repository->find($id);
        return $view->render("users/edit.html.twig", ["user"=>$user]);
    }, "users.edit");

    $app->post("/users/{id}/update", function(ServerRequestInterface $request) use($app)
    {
        $id = $request->getAttribute("id");
        $data = $request->getParsedBody();
        $repository = $app->service("users.repository");
        $repository->update($id, $data);
        return $app->route("users.list");
    }, "users.update");

    $app->get("/users/{id}/show", function(ServerRequestInterface $request) use($app)
    {
        $view = $app->service("view.renderer");
        $id = $request->getAttribute("id");
        $repository = $app->service("users.repository");
        $user = $repository->find($id);
        return $view->render("users/show.html.twig", ["user"=>$user]);
    }, "users.show");

    $app->get("/users/{id}/delete", function(ServerRequestInterface $request) use($app)
    {
        $id = $request->getAttribute("id");
        $repository = $app->service("users.repository");
        $repository->delete($id);
        return $app->route("users.list");
    }, "users.delete");