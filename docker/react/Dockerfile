# pull official base image
FROM node:16.14.0-alpine

# set working directory
WORKDIR /var/www/project/frontend

# add `/app/node_modules/.bin` to $PATH
# ENV PATH /var/www/project/frontend/node_modules/.bin:$PATH

# install app dependencies
# COPY package.json ./
# COPY package-lock.json ./
RUN cd /var/www/project/frontend \ && yarn install

# add app
# COPY . ./

# start app
CMD ["yarn", "start"]
