import { Container, getContainer } from "@cloudflare/containers";
import { Hono } from "hono";

export class MyContainer extends Container {
  defaultPort = 8000;
  sleepAfter = "10s";
}

const app = new Hono<{
  Bindings: {
    MY_CONTAINER: DurableObjectNamespace<MyContainer>;
    ASSETS: Fetcher;
  };
}>();

app.post("/run", async (c) => {
  const id = c.req.param("id");
  const container = getContainer(c.env.MY_CONTAINER, "php-runner");
  return await container.fetch(c.req.raw);
});

app.get("/", async (c) => {
  return c.env.ASSETS.fetch(c.req.raw);
});

export default app;
