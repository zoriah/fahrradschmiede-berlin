import { defineConfig } from "astro/config";
import cloudflare from "@astrojs/cloudflare";
import tailwindcss from "@tailwindcss/vite";
import mdx from "@astrojs/mdx";
import sitemap from "@astrojs/sitemap";
import icon from "astro-icon";

// https://astro.build/config
export default defineConfig({
  output:
    "server" /* <- Wichtig damit Server zu Server kommunikation stattfindet.
    Dadurch können geheime Infos vor Benutzern sicher versteckt gehalten werden!
  */,
  site: "https://astroship.web3templates.com",
  adapter: cloudflare(),
  integrations: [mdx(), sitemap(), icon()],
  vite: {
    plugins: [tailwindcss()],
  },
});
