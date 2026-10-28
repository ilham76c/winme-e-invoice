
FROM node:22-bookworm-slim

WORKDIR /app

RUN npm install -g npm@11

EXPOSE 5173

CMD ["sh", "-c", "npm install && npm run dev -- --host 0.0.0.0"]
