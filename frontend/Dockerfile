# pull official base image
FROM node:16.14.0-alpine

# set working directory
WORKDIR ./

# add `/app/node_modules/.bin` to $PATH
# ENV PATH .//node_modules/.bin:$PATH

# install app dependencies
COPY ./* ./
COPY ./.env ./
# COPY ./yarn.lock ./
RUN cd ./ \ && yarn install

# add app
COPY . ./

# start app
CMD ["yarn", "build"]
CMD ["yarn", "start"]
