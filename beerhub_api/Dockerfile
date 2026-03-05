FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build
WORKDIR /src

# Copy the csproj and restore
COPY beerhub_api.csproj ./
RUN dotnet restore beerhub_api.csproj

# Copy the rest of the source
COPY . .

# Publish
RUN dotnet publish beerhub_api.csproj -c Release -o /app/publish

FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS final
WORKDIR /app
COPY --from=build /app/publish .

EXPOSE 5189
ENV ASPNETCORE_URLS=http://+:5189

# Use your real DLL name (usually matches csproj filename)
ENTRYPOINT ["dotnet", "beerhub_api.dll"]
